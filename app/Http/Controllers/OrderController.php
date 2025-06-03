<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Order', [
            'orders' => $orders->items(),
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'from' => $orders->firstItem() ?? 0,
                'to' => $orders->lastItem() ?? 0,
            ]
        ]);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product']);

        return Inertia::render('OrderDetail', [
            'order' => $order
        ]);
    }

    public function destroy(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'Không thể xóa đơn hàng này');
        }

        $order->delete();
        return back()->with('success', 'Đơn hàng đã được xóa');
    }

    public function cancel(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Không thể hủy đơn hàng này');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Đơn hàng đã được hủy');
    }

    public function create(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'payment' => 'required|in:vnpay,cod',
            'promotion' => 'nullable',
        ]);

        // Get checkout items from session
        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) {
            return redirect()->route('cart')->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng');
        }

        // Get products from database
        $productIds = collect($checkoutItems)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        // Calculate total
        $total = 0;
        foreach ($products as $product) {
            $item = collect($checkoutItems)->firstWhere('product_id', $product->id);
            $total += $product->price * $item['quantity'];
        }
        $promotion = Promotion::find($request->promotion);
        if ($promotion) {
            if ($promotion->type == 'percentage') {
                $total -= ($total * $promotion->value) / 100;
            } else {
                $total -= $promotion->value;
            }
        }
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'payment_method' => $request->payment,
            'order_number' => 'ORD' . Auth::id() . Carbon::now()->format('YmdHis'),
            'total' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($products as $product) {
            $item = collect($checkoutItems)->firstWhere('product_id', $product->id);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'product_name' => $product->name,
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity']
            ]);
        }

        // Xử lý thanh toán tùy theo phương thức
        if ($request->input('payment') === 'vnpay') {
            $urlRedirect = $this->processVNPayPayment($order, $request->ip());

            // Đẩy URL thanh toán vào session flash
            session()->flash('paymentUrl', $urlRedirect);

            // Redirect về route GET để tránh lỗi back
            return redirect()->route('vnpay.processing');
        }

        // Nếu là COD thì chỉ cần trả về thông báo thành công
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được tạo thành công');
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

            if ($responseCode === '00' && $transactionStatus === '00') {

                if ($order->status === 'confirmed') {
                    return inertia('Return', [
                        'paymentStatus' => 'failed',
                        'message' => 'Đơn hàng đã được thanh toán rồi. Vui lòng không tải lại trang!'
                    ]);
                }
                $order->status = 'confirmed';
                $this->handleQuantity($order);
                $order->save();
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
                'paymentStatus' => 'failed',
                'orderId' => $inputData['vnp_TxnRef'] ?? null,
                'transactionNo' => $inputData['vnp_TransactionNo'] ?? null,
                'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
            ]);
        }
    }

    public function handleQuantity(Order $order)
    {
        $orderId = $order->id;
        $userId = $order->user_id;

        // Lấy tất cả OrderItems của đơn hàng
        $orderItems = OrderItem::where('order_id', $orderId)->get();

        // Tính tổng số lượng theo từng product_id (gộp các item cùng product_id)
        $productQuantity = [];
        $productIds = [];

        foreach ($orderItems as $item) {
            if (!isset($productQuantity[$item->product_id])) {
                $productQuantity[$item->product_id] = 0;
            }
            $productQuantity[$item->product_id] += $item->quantity;
            $productIds[] = $item->product_id;
        }

        // Loại bỏ trùng product_id
        $productIds = array_unique($productIds);

        DB::transaction(function () use ($productQuantity, $productIds, $userId) {
            // Lấy sản phẩm kèm số lượng hiện tại
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get();

            foreach ($products as $product) {
                // Kiểm tra số lượng đủ để trừ
                if ($product->stock < $productQuantity[$product->id]) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ số lượng trong kho.");
                }

                // Trừ số lượng
                $product->stock -= $productQuantity[$product->id];
                $product->save();
            }

            // Xoá các CartItem có cùng product_id của user
            CartItem::where('user_id', $userId)
                ->whereIn('product_id', $productIds)
                ->delete();
        });
    }

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
