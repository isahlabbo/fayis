@extends('layouts.guest')

@section('title')
    {{ config('app.name') }} Inventory Rent Receipt
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('finance.inventory.rents') }}" class="btn btn-outline-secondary btn-sm">Back to rents</a>
    <button type="button" class="btn btn-secondary btn-sm" onclick="printContent();">Print</button>
</div>

<div id="receipt-print">
    <div class="receipt-container" style="max-width:280px; margin:0 auto; padding:10px; font-family:'Courier New', monospace; font-size:12px; color:#000;">
        <div style="text-align:center; margin-bottom:10px;">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="max-width:90px; display:block; margin:0 auto 6px;" />
            <div style="font-size:16px; font-weight:700;">{{ config('app.name') }}</div>
            <div style="font-size:13px; margin-top:3px;">Rent Receipt</div>
            <div style="font-size:11px; color:#333; margin-top:4px;">{{ now()->format('Y-m-d H:i') }}</div>
        </div>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>

        <table style="width:100%; font-size:12px; border:1px solid #ddd; border-collapse:collapse;">
            <tr>
                <td style="padding:6px; width:40%; border-bottom:1px solid #ddd;"><strong>Item</strong></td>
                <td style="padding:6px; border-bottom:1px solid #ddd;">{{ optional($rent->item)->name }}{{ optional($rent->item)->sku ? ' ('.optional($rent->item)->sku.')' : '' }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Qty</strong></td>
                <td style="padding:3px 0;">{{ $rent->quantity }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Teacher</strong></td>
                <td style="padding:3px 0;">{{ optional(optional($rent->teacher)->user)->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Date</strong></td>
                <td style="padding:3px 0;">{{ optional($rent->usage_date)->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td style="padding:6px; border-top:1px solid #ddd;"><strong>Notes</strong></td>
                <td style="padding:6px; border-top:1px solid #ddd;">{{ $rent->notes ?? 'N/A' }}</td>
            </tr>
        </table>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>
        <div style="font-size:12px; margin-top:8px;">Sign: ___________________________</div>
        <div style="font-size:12px;">Date: {{ optional($rent->usage_date)->format('Y-m-d') }}</div>
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
