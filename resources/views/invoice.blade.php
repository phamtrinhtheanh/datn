<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn - TechParts Pro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .print-button {
            text-align: right;
            margin-bottom: 20px;
        }

        .print-btn {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .print-btn:hover {
            background-color: #333;
        }

        .invoice-container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 15mm 15mm 20mm 15mm;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
        }

        .company-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 2px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 20px;
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 12px;
            color: #374151;
            background-color: #f9fafb;
        }

        .separator {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 32px;
        }

        .bill-to h3 {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 500;
            letter-spacing: 0.05em;
        }

        .bill-to p {
            margin-bottom: 2px;
            color: #374151;
        }

        .bill-to .customer-name {
            font-weight: 500;
            color: #111;
        }

        .invoice-details {
            text-align: right;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .detail-row .label {
            color: #6b7280;
        }

        .items-table {
            margin-bottom: 32px;
        }

        .table-header {
            display: grid;
            grid-template-columns: 6fr 2fr 2fr 2fr;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 500;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-header .qty-col {
            text-align: center;
        }

        .table-header .price-col,
        .table-header .total-col {
            text-align: right;
        }

        .table-row {
            display: grid;
            grid-template-columns: 6fr 2fr 2fr 2fr;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }

        .item-info .item-name {
            font-weight: 500;
            color: #111;
            margin-bottom: 2px;
        }

        .item-info .item-description {
            font-size: 14px;
            color: #6b7280;
        }

        .table-row .qty {
            text-align: center;
            align-self: center;
        }

        .table-row .price {
            text-align: right;
            align-self: center;
        }

        .table-row .total {
            text-align: right;
            align-self: center;
            font-weight: 500;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 32px;
        }

        .totals-box {
            width: 192px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .total-row .label {
            color: #6b7280;
        }

        .total-separator {
            height: 1px;
            background-color: #e5e7eb;
            margin: 8px 0;
        }

        .final-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 16px;
        }

        .payment-info {
            margin-bottom: 32px;
        }

        .payment-info h3 {
            font-weight: 500;
            margin-bottom: 8px;
            color: #111;
        }

        .payment-info p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 15mm;
            right: 15mm;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .footer p {
            margin-bottom: 4px;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .print-button {
                display: none;
            }

            .invoice-container {
                box-shadow: none;
                margin: 0;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div class="print-button">
    <button class="print-btn" onclick="window.print()">
        In hóa đơn
    </button>
</div>

<div class="invoice-container">
    <!-- Tiêu đề -->
    <div class="header">
        <div class="company-info">
            <h1>Thế Anh Computer</h1>
            <p>32 Thị trấn Đầm Hà</p>
            <p>Đầm Hà, Quảng Ninh</p>
        </div>
        <div class="invoice-title">
            <h2>HÓA ĐƠN</h2>
            <span class="status-badge">{{ $invoice->status ?? 'Đã thanh toán' }}</span>
        </div>
    </div>

    <div class="separator"></div>

    <div class="info-section">
        <div class="bill-to">
            <h3>THÔNG TIN KHÁCH HÀNG</h3>
            <p class="customer-name">{{ $invoice->customer_name }}</p>
            <p>{{ $invoice->customer_address }}</p>
            <p>{{ $invoice->customer_city }}</p>
            <p>{{ $invoice->customer_email }}</p>
        </div>
        <div class="invoice-details">
            <div class="detail-row">
                <span class="label">Số hóa đơn:</span>
                <span>{{ $invoice->number }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Ngày lập:</span>
                <span>{{ $invoice->date }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Hạn thanh toán:</span>
                <span>{{ $invoice->due_date }}</span>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="items-table">
        <div class="table-header">
            <div>SẢN PHẨM</div>
            <div class="qty-col">SL</div>
            <div class="price-col">ĐƠN GIÁ</div>
            <div class="total-col">THÀNH TIỀN</div>
        </div>

        @foreach ($invoice->items as $item)
            <div class="table-row">
                <div class="item-info">
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-description">{{ $item->description }}</div>
                </div>
                <div class="qty">{{ $item->quantity }}</div>
                <div class="price">{{ number_format($item->price, 2, ',', '.') }}₫</div>
                <div class="total">{{ number_format($item->price * $item->quantity, 2, ',', '.') }}₫</div>
            </div>
        @endforeach
    </div>

    <!-- Tổng cộng -->
    <div class="totals-section">
        <div class="totals-box">
            <div class="total-row">
                <span class="label">Tạm tính:</span>
                <span>{{ number_format($invoice->subtotal, 2, ',', '.') }}₫</span>
            </div>
            <div class="total-row">
                <span class="label">Thuế ({{ $invoice->tax_rate }}%):</span>
                <span>{{ number_format($invoice->tax, 2, ',', '.') }}₫</span>
            </div>
            <div class="total-separator"></div>
            <div class="final-total">
                <span>Tổng cộng:</span>
                <span>{{ number_format($invoice->total, 2, ',', '.') }}₫</span>
            </div>
        </div>
    </div>

    <!-- Thông tin thanh toán -->
    <div class="payment-info">
        <h3>Thông tin thanh toán</h3>
        <p>Ngân hàng: {{ $invoice->bank_name }}</p>
        <p>Số tài khoản: {{ $invoice->bank_account }}</p>
        <p>Mã ngân hàng: {{ $invoice->bank_routing }}</p>
    </div>

    <!-- Chân trang -->
    <div class="footer">
        <p>Xin cảm ơn quý khách!</p>
        <p>sales@techpartspro.com • www.techpartspro.com</p>
    </div>
</div>
</body>
</html>
