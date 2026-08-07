@extends('layouts.guest')

@section('title')
    {{ config('app.name') }} Inventory Receipt
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('finance.inventory.usage') }}" class="btn btn-outline-secondary btn-sm">Back to usage</a>
    <button type="button" class="btn btn-secondary btn-sm" onclick="printContent('receipt-print');">Print</button>
</div>

<div id="receipt-print">
    <div class="receipt-container" style="max-width:280px; margin:0 auto; padding:10px; font-family:'Courier New', monospace; font-size:12px; color:#000;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:16px; font-weight:700;">{{ strtoupper(config('app.title')) }}</div>
            <div style="font-size:16px; font-weight:700;">{{ config('app.address') }}</div>
            <div style="font-size:13px; margin-top:3px;">Inventory Usage Receipt</div>
            <div style="font-size:11px; color:#333; margin-top:4px;">{{ now()->format('Y-m-d H:i') }}</div>
        </div>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>

        @if(method_exists($usage, 'generateQrCode'))
            <div style="text-align:center; margin-bottom:10px;">{!! $usage->generateQrCode('Inventory usage receipt: '.$usage->receipt_number, 120) !!}</div>
            <div style="font-size:11px; text-align:center; margin-bottom:10px;">Scan to Verify</div>
        @endif

        <table style="width:100%; font-size:12px; border:1px solid #ddd; border-collapse:collapse;">
            <tr>
                <td style="padding:6px; width:40%; border-bottom:1px solid #ddd;"><strong>Receipt No.</strong></td>
                <td style="padding:6px; border-bottom:1px solid #ddd;">{{ $usage->receipt_number }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Type</strong></td>
                <td style="padding:3px 0;">{{ ucfirst($usage->usage_type) }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Item</strong></td>
                <td style="padding:3px 0;">{{ optional($usage->item)->name }}{{ optional($usage->item)->sku ? ' ('.optional($usage->item)->sku.')' : '' }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Qty</strong></td>
                <td style="padding:3px 0;">{{ $usage->quantity }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Unit</strong></td>
                <td style="padding:3px 0;">{{ number_format($usage->unit_cost, 2) }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Total</strong></td>
                <td style="padding:3px 0;">{{ number_format($usage->total_cost, 2) }}</td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Used By</strong></td>
                <td style="padding:3px 0;">
                    @if($usage->usage_type === 'sale')
                        {{ optional(optional($usage->student)->student)->name ?? 'Student' }}
                        @if(optional(optional($usage->student)->student)->admission_no)
                            ({{ optional($usage->student)->student->admission_no }})
                        @endif
                    @else
                        {{ optional($usage->teacher)->name ?? 'Teacher' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding:3px 0;"><strong>Usage Date</strong></td>
                <td style="padding:3px 0;">{{ optional($usage->usage_date)->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td style="padding:6px; border-top:1px solid #ddd;"><strong>Notes</strong></td>
                <td style="padding:6px; border-top:1px solid #ddd;">{{ $usage->evidence ?? $usage->notes ?? 'N/A' }}</td>
            </tr>
        </table>

        <div style="border-top:1px dashed #000; margin:10px 0;"></div>
        <div style="font-size:12px; margin-top:8px;">Sign: ___________________________</div>
        <div style="font-size:12px;">Date: {{ optional($usage->usage_date)->format('Y-m-d') }}</div>
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
