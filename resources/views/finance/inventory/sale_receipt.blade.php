@extends('layouts.guest')

@section('title')
    {{ config('app.name') }} Inventory Sale Receipt
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('finance.inventory.sales') }}" class="btn btn-outline-secondary btn-sm">Back to sale</a>
    <button type="button" class="btn btn-secondary btn-sm" onclick="printContent();">Print</button>
</div>

<div id="receipt-print">
    <div class="receipt-container" style="max-width:280px; margin:0 auto; padding:10px; font-family: 'Courier New', monospace; font-size:12px; color:#000;">
        <div style="text-align:center; margin-bottom:10px;">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="max-width:90px; display:block; margin:0 auto 6px;" />
            <div style="font-size:16px; font-weight:700;">{{ config('app.name') }}</div>
            <div style="font-size:13px; margin-top:3px;">Sale Receipt</div>
            <div style="font-size:11px; color:#333; margin-top:4px;">{{ now()->format('Y-m-d H:i') }}</div>
        </div>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>

        <table style="width:100%; border-collapse:collapse; font-size:12px; border:1px solid #ddd;">
            <thead>
                <tr>
                    <th style="text-align:left; padding:6px; border-bottom:1px solid #ddd;">Item</th>
                    <th style="text-align:right; padding:6px; border-bottom:1px solid #ddd;">Qty</th>
                    <th style="text-align:right; padding:6px; border-bottom:1px solid #ddd;">Unit</th>
                    <th style="text-align:right; padding:6px; border-bottom:1px solid #ddd;">Amt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $line)
                    <tr>
                        <td style="padding:2px 0;">{{ optional($line->item)->name }}{{ optional($line->item)->sku ? ' ('.optional($line->item)->sku.')' : '' }}</td>
                        <td style="text-align:right; padding:2px 0;">{{ $line->quantity }}</td>
                        <td style="text-align:right; padding:2px 0;">{{ number_format($line->unit_cost, 2) }}</td>
                        <td style="text-align:right; padding:2px 0;">{{ number_format($line->amount, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align:right; padding:6px; font-weight:700; border-top:1px solid #ddd;">Total</td>
                    <td style="text-align:right; padding:6px; font-weight:700; border-top:1px solid #ddd;">{{ number_format($sale->total_cost, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>

        <table style="width:100%; font-size:12px;">
            <tr>
                <td style="padding:3px 0; width:35%;"><strong>Student</strong></td>
                <td style="padding:3px 0;">{{ optional(optional($sale->student)->student)->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Admission</strong></td>
                <td style="padding:3px 0;">{{ optional(optional($sale->student)->student)->admission_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Date</strong></td>
                <td style="padding:3px 0;">{{ optional($sale->usage_date)->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Evidence</strong></td>
                <td style="padding:3px 0;">{{ $sale->evidence ?? 'N/A' }}</td>
            </tr>
        </table>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>
        <div style="font-size:12px; margin-top:8px;">Sign: ___________________________</div>
        <div style="font-size:12px;">Date: {{ optional($sale->usage_date)->format('Y-m-d') }}</div>
    </div>
</div>

<script>
    window.printContent = function() {
        window.print();
    }
</script>
<style>
    @media print {
        .btn { display: none !important; }
        .d-flex.justify-content-between { display: none !important; }
    }
</style>
@endsection
