@extends('layouts.guest')
@section('title','Sales Receipt #'.$sale->id)
@section('content')
<div class="receipt-actions"><a href="{{route('finance.inventory.sales')}}" class="btn btn-light btn-sm">Back to sales</a><button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Print receipt</button></div>
<main class="thermal-receipt">
    <div class="receipt-center"><h1>{{config('app.title')}}</h1><div class="school-address">{{config('app.address')}}</div><div>{{config('app.contact')}}</div><h2>SALES RECEIPT</h2></div>
    <div class="dash"></div>
    <div class="line"><span>Receipt</span><strong>#{{$sale->id}}</strong></div>
    <div class="line"><span>Date</span><strong>{{optional($sale->usage_date)->format('Y-m-d')}}</strong></div>
    <div class="line"><span>Student</span><strong>{{optional(optional($sale->student)->student)->name ?? 'N/A'}}</strong></div>
    <div class="line"><span>Admission No.</span><strong>{{optional(optional($sale->student)->student)->admission_no ?? '-'}}</strong></div>
    <div class="line"><span>Class</span><strong>{{optional(optional($sale->student)->sectionClass)->name ?? '-'}}</strong></div>
    <div class="dash"></div>
    <div class="item-head"><span>Item / Qty</span><span>Amount</span></div>
    @foreach($sale->saleItems as $line)
        <div class="sale-line"><div><strong>{{optional($line->item)->name}}</strong><br><small>{{$line->quantity}} x {{number_format($line->unit_cost,2)}}</small></div><strong>{{number_format($line->amount,2)}}</strong></div>
    @endforeach
    <div class="total"><span>TOTAL</span><span>{{number_format($sale->total_cost,2)}}</span></div>
    <div class="dash"></div><div class="line"><span>Payment method</span><strong>{{$sale->payment_method}}</strong></div>
    <p class="thanks">Thank you<br>Keep this receipt for your records.</p>
</main>
<style>.receipt-actions{max-width:100mm;margin:18px auto;display:flex;justify-content:space-between}.thermal-receipt{width:100mm;max-width:100mm;box-sizing:border-box;margin:0 auto;padding:3mm 2mm 5mm;font:24px/1.25 monospace;color:#000;background:#fff;break-inside:avoid;page-break-inside:avoid}.receipt-center{text-align:center}.receipt-center h1{font-size:40px;margin:0}.receipt-center h2{font-size:32px;margin:4px 0}.school-address{font-size:22px}.dash{border-top:1px dashed #000;margin:5px 0}.line,.total,.sale-line,.item-head{display:flex;justify-content:space-between;gap:6px;margin:5px 0;break-inside:avoid;page-break-inside:avoid}.line strong,.sale-line>strong{text-align:right}.item-head{font-weight:bold;border-bottom:1px solid #000}.sale-line small{font-size:.82em}.total{font-size:34px;font-weight:bold;border-top:2px solid #000;border-bottom:2px solid #000;padding:7px 0}.thanks{text-align:center;margin:10px 0 2px;break-inside:avoid;page-break-inside:avoid}@media print{@page{size:100mm 350mm;margin:0}html,body{width:100mm!important;height:350mm!important;margin:0!important;padding:0!important;background:#fff!important;overflow:hidden!important}body *{visibility:hidden!important}.thermal-receipt,.thermal-receipt *{visibility:visible!important}.thermal-receipt{position:absolute!important;left:0!important;top:0!important;width:100mm!important;max-width:100mm!important;box-sizing:border-box!important;margin:0!important;padding:0 1mm!important;font-size:28px!important;line-height:1.2!important;box-shadow:none!important;overflow:visible!important;break-inside:avoid!important;page-break-inside:avoid!important;break-after:avoid!important;page-break-after:avoid!important}.receipt-center h1{font-size:44px!important}.receipt-center h2{font-size:34px!important;margin:2px 0!important}.school-address{font-size:24px!important}.dash{margin:3px 0!important}.line,.total,.sale-line,.item-head{margin:4px 0!important;break-inside:avoid!important;page-break-inside:avoid!important}.total{font-size:36px!important;padding:6px 0!important}.thanks{margin-top:8px!important}.receipt-actions{display:none!important}}</style>
<style>@media print{@page{size:auto!important;margin:0!important}html,body,body>section.container{width:100%!important;max-width:none!important;min-width:0!important;margin:0!important;padding:0!important}.thermal-receipt{position:absolute!important;inset:0 auto auto 0!important;width:100%!important;max-width:none!important;margin:0!important;padding:0 1%!important;box-sizing:border-box!important}.thermal-receipt .line,.thermal-receipt .total,.thermal-receipt .sale-line,.thermal-receipt .item-head{width:100%!important;max-width:none!important}}</style>
@endsection
