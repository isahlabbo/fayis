<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Material Collection Sheet</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <style>
    @page { size: A4 landscape; margin: 12mm; }
    body { font-family: Arial, sans-serif; font-size: 13px; color: #222; margin: 0; }
    .page { padding: 20px; }
    .page-header { text-align: center; margin-bottom: 15px; }
    .page-header img { width: 60px; }
    .page-header h4 { margin: 5px 0 0; }
    table.collection-sheet { width: 100%; border-collapse: collapse; margin-top: 15px; }
    table.collection-sheet thead { display: table-header-group; }
    table.collection-sheet tr { page-break-inside: avoid; }
    table.collection-sheet th, table.collection-sheet td { border: 1px solid #333; padding: 6px 8px; text-align: left; vertical-align: top; }
    table.collection-sheet th { background: #f0f0f0; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; }
    .student-cell { line-height: 1.8; }
    .checkbox { display: inline-block; width: 16px; height: 16px; border: 1px solid #333; vertical-align: middle; }
    .material-list { list-style: none; margin: 0; padding: 0; }
    .material-list li { line-height: 1.8; }
    .signature-line { border-bottom: 1px solid #333; display: inline-block; width: 140px; height: 14px; vertical-align: bottom; }
    .collection-field { line-height: 2.2; white-space: nowrap; }
    #print-btn { margin: 15px; }
    @media print {
      #print-btn { display: none; }
      .page { padding: 0; }
    }
  </style>
</head>
<body>

@if(Auth::user()->hasPermission('manage-material-collection'))
  <div class="text-center">
    <button id="print-btn" class="btn btn-primary" onclick="window.print()">Print Material Collection Sheet</button>
  </div>
@endif

<div class="page">
  <div class="page-header">
    <img src="{{ asset('images/logo.jpg') }}" alt="FAYIS">
    <h4>FAYIS</h4>
    <p>
      Material Collection Sheet @if($session) &mdash; {{ $session->name }} @endif<br>
      Class: {{ $targetClass->section->name }} / {{ $targetClass->name }}
    </p>
  </div>

  <table class="collection-sheet">
    <thead>
    <tr>
      <th width="8%">Picture</th>
      <th width="27%">Student</th>
      <th width="27%">Collection</th>
      <th width="38%">Material</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
      <tr>
        <td>
          <img class="student-photo" src="{{ $record->student->picture ? Storage::url($record->student->picture) : asset('images/user.jpg') }}" alt="{{ $record->student->name }}">
        </td>
        <td class="student-cell">
          <strong>{{ $record->student->name }}</strong><br>
          Admission No: {{ $record->student->admission_no }}<br>
          Class: {{ $targetClass->section->name }} / {{ $targetClass->name }}<br>
          Guardian: {{ optional($record->student->guardian)->name ?? '—' }}<br>
          Guardian Phone: {{ optional($record->student->guardian)->phone ?? '—' }}
        </td>
        <td>
          <div class="collection-field">Name of Receiver: <span class="signature-line">&nbsp;</span></div>
          <div class="collection-field">Signature: <span class="signature-line">&nbsp;</span></div>
          <div class="collection-field">Date Received: <span class="signature-line">&nbsp;</span></div>
        </td>
        <td>
          <ul class="material-list">
            @foreach($materials as $material)
              <li><span class="checkbox"></span> {{ $material }}</li>
            @endforeach
          </ul>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>


</body>
</html>
