<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Order Receipt {{ $order->order_number }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; color: #1A1A1A; font-size: 13px; }

  .header { background-color: #1B4D1B; color: #ffffff; padding: 28px 40px; }
  .header-title { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
  .header-sub { font-size: 12px; margin-top: 4px; opacity: 0.8; }

  .meta-row { display: flex; padding: 20px 40px; border-bottom: 1px solid #E5E7EB; gap: 60px; }
  .meta-item .meta-label { font-size: 10px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.08em; }
  .meta-item .meta-value { font-size: 14px; font-weight: bold; margin-top: 3px; color: #1A1A1A; }

  .section { padding: 20px 40px; }
  .section-title { font-size: 10px; color: #6B7280; text-transform: uppercase;
                   letter-spacing: 0.08em; padding-bottom: 8px;
                   border-bottom: 1px solid #E5E7EB; margin-bottom: 14px; }

  .customer-row { display: flex; gap: 60px; }
  .customer-field .field-label { font-size: 10px; color: #9CA3AF; text-transform: uppercase; }
  .customer-field .field-value { font-size: 13px; font-weight: 500; margin-top: 3px; }

  table { width: 100%; border-collapse: collapse; margin-top: 0; }
  table thead tr { background-color: #F3F4F6; }
  table thead th { padding: 9px 12px; text-align: left; font-size: 11px;
                   color: #6B7280; text-transform: uppercase; letter-spacing: 0.05em; }
  table tbody tr { border-bottom: 1px solid #F9FAFB; }
  table tbody td { padding: 11px 12px; font-size: 13px; }
  table tfoot tr { background-color: #F0FDF4; }
  table tfoot td { padding: 12px; font-size: 14px; font-weight: bold; color: #1B4D1B; }
  .text-right { text-align: right; }

  .notes-box { background-color: #F9FAFB; border: 1px solid #E5E7EB;
               border-radius: 6px; padding: 12px 16px; font-size: 13px;
               color: #4B5563; }

  .footer { margin: 10px 40px 0; padding: 16px 0;
            border-top: 1px solid #E5E7EB; text-align: center;
            font-size: 11px; color: #9CA3AF; line-height: 1.6; }

  .status-badge { display: inline-block; background-color: #DCFCE7;
                  color: #166534; padding: 3px 10px; border-radius: 4px;
                  font-size: 11px; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
  <div class="header-title">🐾 {{ $storeName }}</div>
  <div class="header-sub">Order Receipt — Thank you for your purchase!</div>
</div>

<div class="meta-row">
  <div class="meta-item">
    <div class="meta-label">Order Number</div>
    <div class="meta-value">{{ $order->order_number }}</div>
  </div>
  <div class="meta-item">
    <div class="meta-label">Date & Time</div>
    <div class="meta-value">{{ $order->created_at->format('d M Y, h:i A') }}</div>
  </div>
  <div class="meta-item">
    <div class="meta-label">Status</div>
    <div class="meta-value"><span class="status-badge">Order Received</span></div>
  </div>
</div>

<div class="section">
  <div class="section-title">Customer Information</div>
  <div class="customer-row">
    <div class="customer-field">
      <div class="field-label">Full Name</div>
      <div class="field-value">{{ $order->customer_name }}</div>
    </div>
    <div class="customer-field">
      <div class="field-label">Phone Number</div>
      <div class="field-value">{{ $order->customer_phone }}</div>
    </div>
  </div>
</div>

<div class="section">
  <div class="section-title">Order Items</div>
  <table>
    <thead>
      <tr>
        <th>Product Name</th>
        <th class="text-right">Unit Price</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Line Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
      <tr>
        <td>{{ $item['name'] }}</td>
        <td class="text-right">EGP {{ number_format($item['price'], 2) }}</td>
        <td class="text-right">{{ $item['qty'] ?? 1 }}</td>
        <td class="text-right">EGP {{ number_format($item['price'] * ($item['qty'] ?? 1), 2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="text-right">Total Amount</td>
        <td class="text-right">EGP {{ number_format($order->total, 2) }}</td>
      </tr>
    </tfoot>
  </table>
</div>

@if($order->notes)
<div class="section">
  <div class="section-title">Customer Notes</div>
  <div class="notes-box">{{ $order->notes }}</div>
</div>
@endif

<div class="footer">
  <strong>{{ $storeName }}</strong><br>
  We will contact you at {{ $order->customer_phone }} within 24 hours to confirm your delivery.<br>
  Thank you for shopping with us! 🐾
</div>

</body>
</html>
