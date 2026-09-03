<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 12px; }
        * { box-sizing: border-box; }
        body { color: #111; font-family: DejaVu Sans, sans-serif; font-size: 8.5px; line-height: 1.15; margin: 0; }
        .card-body { padding: 0; }
        .row { width: 100%; clear: both; }
        .row:after { content: ""; display: table; clear: both; }
        .col-md-12 { width: 100%; }
        .col-md-10 { width: 82%; float: left; }
        .col-md-6 { width: 50%; float: left; padding: 2px; }
        .col-md-5 { width: 42%; float: left; padding: 2px; }
        .col-md-4 { width: 33%; float: left; padding: 2px; }
        .col-md-2 { width: 16%; float: left; padding: 2px; }
        .col-md-1 { width: 8%; float: left; min-height: 1px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-0 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { overflow-wrap: break-word; }
        .table-bordered th, .table-bordered td { border: 1px solid #555; padding: 2px; }
        h2, h3, h4, p { margin: 2px 0; }
        img { max-width: 100%; }
    </style>
</head>
<body>
    @include('section.class.student.result.reportcard.view')
</body>
</html>
