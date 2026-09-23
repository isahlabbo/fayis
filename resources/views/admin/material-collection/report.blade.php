<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Material Collection Report</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <style>
    @page { size: A4 landscape; margin: 12mm; }
    body { font-family: Arial, sans-serif; font-size: 13px; color: #222; margin: 0; }
    .page { padding: 20px; }
    .page-header { text-align: center; margin-bottom: 15px; }
    .page-header img { width: 60px; }
    .page-header h4 { margin: 5px 0 0; }
    h5.section-title { margin-top: 25px; }
    table.report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table.report-table th, table.report-table td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
    table.report-table th { background: #f0f0f0; }
    #print-btn { margin: 15px; }
    @media print {
      #print-btn { display: none; }
    }
  </style>
</head>
<body>

@if(Auth::user()->hasPermission('manage-material-collection'))
  <div class="text-center">
    <button id="print-btn" class="btn btn-primary" onclick="window.print()">Print Report</button>
  </div>
@endif

<div class="page">
  <div class="page-header">
    <img src="{{ asset('images/logo.jpg') }}" alt="FAYIS">
    <h4>FAYIS</h4>
    <p>Material Collection Report</p>
  </div>

  <h5 class="section-title">Collected ({{ $collected->count() }})</h5>
  <table class="report-table">
    <tr>
      <th width="5%">S/N</th>
      <th width="25%">Student</th>
      <th width="15%">Admission No</th>
      <th width="20%">Class</th>
      <th width="15%">Receiver</th>
      <th width="20%">Date Collected</th>
    </tr>
    @forelse($collected as $index => $record)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $record->sectionClassStudent->student->name }}</td>
        <td>{{ $record->sectionClassStudent->student->admission_no }}</td>
        <td>{{ optional($record->targetSectionClass->section)->name }} / {{ $record->targetSectionClass->name }}</td>
        <td>{{ $record->receiver_name }}</td>
        <td>{{ optional($record->collected_at)->format('d M Y, h:i A') }}</td>
      </tr>
    @empty
      <tr><td colspan="6" class="text-center">No students have collected their materials yet.</td></tr>
    @endforelse
  </table>

  <h5 class="section-title">Not Collected ({{ $notCollected->count() }})</h5>
  <table class="report-table">
    <tr>
      <th width="5%">S/N</th>
      <th width="35%">Student</th>
      <th width="20%">Admission No</th>
      <th width="40%">Class</th>
    </tr>
    @forelse($notCollected as $index => $record)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $record->sectionClassStudent->student->name }}</td>
        <td>{{ $record->sectionClassStudent->student->admission_no }}</td>
        <td>{{ optional($record->targetSectionClass->section)->name }} / {{ $record->targetSectionClass->name }}</td>
      </tr>
    @empty
      <tr><td colspan="4" class="text-center">All students have collected their materials.</td></tr>
    @endforelse
  </table>
</div>

</body>
</html>
