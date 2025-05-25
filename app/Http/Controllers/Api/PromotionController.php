<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $promotion = Promotion::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promotion) {
            return response()->json([
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn',
            ], 404);
        }

        if ($request->total < $promotion->min_order_amount) {
            return response()->json([
                'message' => "Đơn hàng phải có giá trị tối thiểu là " . number_format($promotion->min_order_amount) . "đ",
            ], 400);
        }

        if ($promotion->max_uses !== null && $promotion->max_uses <= 0) {
            return response()->json([
                'message' => 'Mã khuyến mãi đã hết lượt sử dụng',
            ], 400);
        }

        $discount = $promotion->type === 'percentage'
            ? ($request->total * $promotion->value / 100)
            : $promotion->value;

        return response()->json([
            'promotion' => $promotion,
            'discount' => $discount,
            'final_total' => $request->total - $discount,
        ]);
    }
} 