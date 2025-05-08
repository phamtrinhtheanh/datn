<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use DateTime;
use DateTimeZone;

class VNPayService
{
    public function createPayment($orderId, $amount, $orderInfo, $ipAddress)
    {

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
        $vnp_TmnCode = "YJM5XKIB";//Mã website tại VNPAY
        $vnp_HashSecret = "P1UKV87MJODS2ULZTS8I9X84PG84EGOU"; //Chuỗi bí mật

        $vnp_TxnRef = "donhang1";
        $vnp_OrderInfo = "Thanh";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 20000*100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $ipAddress ?? request()->ip();
        //Add Params of 2.0.1 Version
        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $vnp_ExpireDate = new DateTime('now', $timezone);
        $vnp_ExpireDate->modify('+15 minutes');
        $vnp_ExpireDate = $vnp_ExpireDate->format('YmdHis');
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
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $hashdata = '';
        $query = '';
        $query = '';
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        ;

// Generate secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $query .= 'vnp_SecureHash=' . $vnpSecureHash;


        $vnp_Url = $vnp_Url . "?" . $query;

        return $vnp_Url;
    }

    private function sanitizeOrderInfo($orderInfo)
    {
        $orderInfo = $this->removeAccents($orderInfo);
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $orderInfo);
    }

    private function removeAccents($str)
    {
        $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
        return $transliterator->transliterate($str);
    }
}
