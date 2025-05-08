<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Xác minh chữ ký (nếu VNPAY có cung cấp)
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        $secretKey = config('services.vnpay.hash_secret');

        // Bỏ các trường không cần thiết khi tính hash
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $i = 0;
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . $key . '=' . $value;
            } else {
                $hashData .= $key . '=' . $value;
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $secretKey);

        if ($secureHash !== $vnp_SecureHash) {
            Log::error('VNPAY Webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Xử lý kết quả thanh toán
        $orderId = $inputData['vnp_TxnRef'];
        $amount = $inputData['vnp_Amount'] / 100; // VNPAY gửi amount nhân 100
        $responseCode = $inputData['vnp_ResponseCode'];
        $transactionNo = $inputData['vnp_TransactionNo'];

        if ($responseCode === '00') {
            // Thanh toán thành công
            // Cập nhật đơn hàng trong database
            // Gửi email xác nhận, v.v.

            Log::info("VNPAY Payment Success: Order $orderId, Amount $amount, Transaction $transactionNo");
        } else {
            // Thanh toán thất bại
            Log::error("VNPAY Payment Failed: Order $orderId, Response Code $responseCode");
        }

        return response()->json(['success' => true]);
    }
}
