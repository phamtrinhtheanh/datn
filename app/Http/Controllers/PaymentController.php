<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\VNPayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }
    public function checkout(Request $request) {

        $request->validate(['product_ids' => 'required|array']);

        // Lấy danh sách product_ids từ yêu cầu
        $product_ids = $request->input('product_ids');

        // Lấy người dùng hiện tại
        $user = Auth::user();

        // Lấy hoặc tạo giỏ hàng của người dùng
        $cart = $user->cart()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Lọc các sản phẩm trong giỏ hàng dựa trên product_ids
        $cartItems = $cart->items()
            ->with('product')
            ->whereIn('product_id', $product_ids)  // Lọc theo product_ids
            ->get();


        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;  // Tính subtotal cho mỗi sản phẩm
        });

        return Inertia::render('Checkout', [
            'products' => $cartItems,
            'total' => $total,
        ]);
    }

    public function order(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'payment' => 'required|string|in:vnpay,cod',
            'notes' => 'nullable|string|max:1000',
            'totalAmount' => 'required|numeric|min:0',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        $order = DB::transaction(function () use ($validated) {
            // Tạo order
            $order = Order::create([
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['phone'],
                'shipping_address' => $validated['address'],
                'status' => 'processing',
                'total' => $validated['totalAmount'],
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id() ?? null, // Nếu có user đăng nhập
            ]);

            // Tạo các order items
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }

            return $order;
        });

        // Xử lý thanh toán tùy theo phương thức
        if ($validated['payment'] === 'vnpay') {
            $urlRedirect = $this->processVNPayPayment($order, $request->ip());

            // Đẩy URL thanh toán vào session flash
            session()->flash('paymentUrl', $urlRedirect);

            // Redirect về route GET để tránh lỗi back
            return redirect()->route('vnpay.processing');
        }

        // Nếu là COD thì chỉ cần trả về thông báo thành công
        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được tạo thành công',
            'order' => $order,
        ]);
    }

    protected function processVNPayPayment(Order $input, $ip)
    {

        session(['url_prev' => url()->previous()]);
        $vnp_TmnCode = "YJM5XKIB";
        $vnp_HashSecret = "KG0S9B84LJUWJA68QAWF82PB42HSG7X4";
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_ReturnUrl = "http://127.0.0.1:8000/return-vnpay";
        $order = (object)[
            "code" => $input->order_number,
            "total" => $input->total,
            "bankCode" => 'NCB',
            "type" => "billpayment",
            "info" => "Thanh toán đơn hàng",
        ];

        $vnp_TxnRef = $order->code;
        $vnp_OrderInfo = $order->info;
        $vnp_OrderType = $order->type;
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $order->bankCode;
        $vnp_IpAddr = $ip;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        // Tạo mã băm bảo mật (Secure Hash) bằng cách sử dụng thuật toán SHA-512 với hash secret
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        // Thêm mã băm bảo mật vào URL để đảm bảo tính toàn vẹn của dữ liệu
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

    public function handleReturn(Request $request)
    {
        $inputData = $request->all();
        Log::info('📩 VNPAY IPN Received:', $request->all());

        try {
            // 1. Kiểm tra checksum
            $vnp_HashSecret = "KG0S9B84LJUWJA68QAWF82PB42HSG7X4";
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            unset($inputData['vnp_SecureHash']);

            ksort($inputData);
            $hashData = '';
            $i = 0;

            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash !== $vnp_SecureHash) {
                Log::error('❌ Invalid checksum', [
                    'received' => $vnp_SecureHash,
                    'calculated' => $secureHash
                ]);
                return response()->json([
                    'RspCode' => '97',
                    'Message' => 'Invalid checksum'
                ]);
            }

            // 2. Lấy thông tin giao dịch
            $orderCode = $inputData['vnp_TxnRef'] ?? null;
            $transactionNo = $inputData['vnp_TransactionNo'] ?? null;
            $amount = isset($inputData['vnp_Amount']) ? (int)$inputData['vnp_Amount'] / 100 : 0;
            $responseCode = $inputData['vnp_ResponseCode'] ?? null;
            $transactionStatus = $inputData['vnp_TransactionStatus'] ?? null;

            Log::info('🔍 Processing IPN for order: ' . $orderCode, [
                'amount' => $amount,
                'response_code' => $responseCode,
                'transaction_status' => $transactionStatus
            ]);

            // 3. Kiểm tra đơn hàng
            $order = Order::where('order_number', $orderCode)->first();

            if (!$order) {
                Log::error('❌ Order not found', ['order_code' => $orderCode]);
                return response()->json([
                    'RspCode' => '01',
                    'Message' => 'Order not found'
                ]);
            }

            // 4. Kiểm tra số tiền
            if ($amount != $order->total) {
                Log::error('❌ Invalid amount', [
                    'expected' => $order->total,
                    'received' => $amount
                ]);
                return response()->json([
                    'RspCode' => '04',
                    'Message' => 'Invalid amount'
                ]);
            }

            // 5. Kiểm tra trạng thái giao dịch
            // VNPay có 2 trường thể hiện trạng thái:
            // - vnp_ResponseCode: '00' = thành công
            // - vnp_TransactionStatus: '00' = thành công
            if ($responseCode === '00' && $transactionStatus === '00') {
                // Kiểm tra trạng thái hiện tại của đơn hàng
                if ($order->status === 'confirmed') {
                    return inertia('Return', [
                        'paymentStatus' => 'failed',
                        'message' => 'Đơn hàng đã được thanh toán rồi. Vui lòng không tải lại trang!'
                    ]);
                }

                // Cập nhật trạng thái đơn hàng
                DB::transaction(function () use ($order, $transactionNo, $inputData) {
                    $order->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_no' => $transactionNo,
                        'paid_at' => $this->parseVnpayDate($inputData['vnp_PayDate'] ?? null),
                        'bank_code' => $inputData['vnp_BankCode'] ?? null,
                        'card_type' => $inputData['vnp_CardType'] ?? null,
                        'bank_transaction_no' => $inputData['vnp_BankTranNo'] ?? null,
                    ]);

                    Log::info('✅ Order confirmed successfully', [
                        'order_id' => $order->id,
                        'new_status' => 'confirmed'
                    ]);
                });

                return inertia('Return', [
                    'paymentStatus' => $inputData['vnp_ResponseCode'] === '00' ? 'success' : 'failed',
                    'orderId' => $inputData['vnp_TxnRef'] ?? null,
                    'transactionNo' => $inputData['vnp_TransactionNo'] ?? null,
                    'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
                ]);
            } else {
                Log::error('❌ Transaction failed', [
                    'response_code' => $responseCode,
                    'transaction_status' => $transactionStatus
                ]);
                return inertia('Return', [
                    'paymentStatus' => $inputData['vnp_ResponseCode'] === '00' ? 'success' : 'failed',
                    'orderId' => $inputData['vnp_TxnRef'] ?? null,
                    'transactionNo' => $inputData['vnp_TransactionNo'] ?? null,
                    'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('🔥 IPN Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return inertia('Return', [
                'paymentStatus' => $inputData['vnp_ResponseCode'] === '00' ? 'success' : 'failed',
                'orderId' => $inputData['vnp_TxnRef'] ?? null,
                'transactionNo' => $inputData['vnp_TransactionNo'] ?? null,
                'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
            ]);
        }

    }
    public function vnpayIpn(Request $request)
    {
        Log::info('📩 VNPAY IPN Received:', $request->all());

        try {
            // 1. Kiểm tra checksum
            $vnp_HashSecret = "KG0S9B84LJUWJA68QAWF82PB42HSG7X4";
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            unset($inputData['vnp_SecureHash']);

            ksort($inputData);
            $hashData = '';
            $i = 0;

            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash !== $vnp_SecureHash) {
                Log::error('❌ Invalid checksum', [
                    'received' => $vnp_SecureHash,
                    'calculated' => $secureHash
                ]);
                return response()->json([
                    'RspCode' => '97',
                    'Message' => 'Invalid checksum'
                ]);
            }

            // 2. Lấy thông tin giao dịch
            $orderCode = $inputData['vnp_TxnRef'] ?? null;
            $transactionNo = $inputData['vnp_TransactionNo'] ?? null;
            $amount = isset($inputData['vnp_Amount']) ? (int)$inputData['vnp_Amount'] / 100 : 0;
            $responseCode = $inputData['vnp_ResponseCode'] ?? null;
            $transactionStatus = $inputData['vnp_TransactionStatus'] ?? null;

            Log::info('🔍 Processing IPN for order: ' . $orderCode, [
                'amount' => $amount,
                'response_code' => $responseCode,
                'transaction_status' => $transactionStatus
            ]);

            // 3. Kiểm tra đơn hàng
            $order = Order::where('order_number', $orderCode)->first();

            if (!$order) {
                Log::error('❌ Order not found', ['order_code' => $orderCode]);
                return response()->json([
                    'RspCode' => '01',
                    'Message' => 'Order not found'
                ]);
            }

            // 4. Kiểm tra số tiền
            if ($amount != $order->total) {
                Log::error('❌ Invalid amount', [
                    'expected' => $order->total,
                    'received' => $amount
                ]);
                return response()->json([
                    'RspCode' => '04',
                    'Message' => 'Invalid amount'
                ]);
            }

            // 5. Kiểm tra trạng thái giao dịch
            // VNPay có 2 trường thể hiện trạng thái:
            // - vnp_ResponseCode: '00' = thành công
            // - vnp_TransactionStatus: '00' = thành công
            if ($responseCode === '00' && $transactionStatus === '00') {
                // Kiểm tra trạng thái hiện tại của đơn hàng
                if ($order->status === 'confirmed') {
                    Log::info('ℹ️ Order already confirmed', ['order_id' => $order->id]);
                    return response()->json([
                        'RspCode' => '02',
                        'Message' => 'Order already confirmed'
                    ]);
                }

                // Cập nhật trạng thái đơn hàng
                DB::transaction(function () use ($order, $transactionNo, $inputData) {
                    $order->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_no' => $transactionNo,
                        'paid_at' => $this->parseVnpayDate($inputData['vnp_PayDate'] ?? null),
                        'bank_code' => $inputData['vnp_BankCode'] ?? null,
                        'card_type' => $inputData['vnp_CardType'] ?? null,
                        'bank_transaction_no' => $inputData['vnp_BankTranNo'] ?? null,
                    ]);

                    Log::info('✅ Order confirmed successfully', [
                        'order_id' => $order->id,
                        'new_status' => 'confirmed'
                    ]);
                });

                return response()->json([
                    'RspCode' => '00',
                    'Message' => 'Confirm success'
                ]);
            } else {
                Log::error('❌ Transaction failed', [
                    'response_code' => $responseCode,
                    'transaction_status' => $transactionStatus
                ]);
                return response()->json([
                    'RspCode' => '99',
                    'Message' => 'Transaction failed or pending'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('🔥 IPN Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'RspCode' => '99',
                'Message' => 'Internal server error'
            ]);
        }
    }

// Hàm hỗ trợ chuyển đổi định dạng ngày từ VNPay
    protected function parseVnpayDate($vnpayDate)
    {
        if (!$vnpayDate) return now();

        try {
            return Carbon::createFromFormat('YmdHis', $vnpayDate);
        } catch (\Exception $e) {
            Log::warning('⚠️ Failed to parse VNPay date', [
                'date' => $vnpayDate,
                'error' => $e->getMessage()
            ]);
            return now();
        }
    }
}
