<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // Xem hóa đơn trên trình duyệt
    public function show(Order $order)
    {
        $invoice = $this->buildInvoiceObject($order);
        return view('invoice', ['invoice' => $invoice]);
    }

    // Tải hóa đơn dạng PDF
    public function download(Order $order)
    {
        $invoice = $this->buildInvoiceObject($order);

        PDF::setOptions([
            'defaultFont' => 'DejaVu Sans'
        ]);

        $pdf = PDF::loadView('invoice', ['invoice' => $invoice]);

        return $pdf->download("hoa-don-{$order->order_number}.pdf");
    }

    private function buildInvoiceObject(Order $order): object
    {
        $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
        $tax = round($subtotal * 0.1, 2);

        return (object)[
            'status' => $order->status,
            'customer_name' => $order->customer_name,
            'customer_address' => $order->address,
            'customer_city' => '',
            'customer_email' => $order->email,
            'number' => $order->order_number,
            'date' => $order->created_at->format('d/m/Y'),
            'due_date' => $order->created_at->addDays(7)->format('d/m/Y'),
            'items' => $order->items->map(function ($item) {
                return (object)[
                    'name' => $item->product_name,
                    'description' => '',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'subtotal' => $subtotal,
            'tax_rate' => 10,
            'tax' => $tax,
            'total' => $order->total,
            'bank_name' => 'Vietcombank',
            'bank_account' => '123456789',
            'bank_routing' => 'VCB001',
        ];
    }
}
