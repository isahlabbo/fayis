<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\Term;

class PaymentReportController extends Controller
{
    protected function renderPdfView(string $view, array $data, string $filename)
    {
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data);
            return $pdf->download($filename);
        }

        if (class_exists(\Dompdf\Dompdf::class)) {
            $html = view($view, $data)->render();
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        }

        return response()->view($view, array_merge($data, ['pdf_unavailable' => true]));
    }

    public function index(Request $request)
    {
        $sections = Section::orderBy('name')->get();
        $classes = SectionClass::orderBy('name')->get();
        $terms = Term::orderBy('name')->get();

        $payments = Payment::with(['sectionClassStudent.sectionClass.section', 'sectionClassStudent.student', 'term', 'sectionClassFee.fee', 'user'])
            ->when($request->query('section'), fn($query, $value) => $query->whereHas('sectionClassStudent.sectionClass', fn($query) => $query->where('section_id', $value)))
            ->when($request->query('section_class'), fn($query, $value) => $query->whereHas('sectionClassStudent', fn($query) => $query->where('section_class_id', $value)))
            ->when($request->query('term'), fn($query, $value) => $query->where('term_id', $value))
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('date', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('date', '<=', $value))
            ->orderBy('date', 'desc')
            ->get();

        $totals = [
            'count' => $payments->count(),
            'amount' => $payments->sum('amount'),
        ];

        return view('finance.payments.report', [
            'sections' => $sections,
            'classes' => $classes,
            'terms' => $terms,
            'payments' => $payments,
            'selectedSection' => $request->query('section'),
            'selectedClass' => $request->query('section_class'),
            'selectedTerm' => $request->query('term'),
            'fromDate' => $request->query('from_date'),
            'toDate' => $request->query('to_date'),
            'totals' => $totals,
        ]);
    }

    public function pdf(Request $request)
    {
        $payments = $this->buildQuery($request)->get();

        return $this->renderPdfView('finance.payments.report_pdf', [
            'payments' => $payments,
            'filters' => $this->filterLabels($request),
        ], 'payment-report.pdf');
    }

    public function csv(Request $request)
    {
        $payments = $this->buildQuery($request)->get();
        $filename = 'payment-report-'.date('YmdHis').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = ['Date', 'Section', 'Class', 'Student', 'Term', 'Fee', 'Amount', 'Mode', 'Recorded By'];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->date,
                    $payment->sectionClassStudent->sectionClass->section->name ?? '-',
                    $payment->sectionClassStudent->sectionClass->name ?? '-',
                    $payment->sectionClassStudent->student->name ?? '-',
                    $payment->term->name ?? '-',
                    $payment->sectionClassFee->fee->name ?? '-',
                    number_format($payment->amount, 2),
                    $payment->mode,
                    $payment->user->name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function buildQuery(Request $request)
    {
        return Payment::with(['sectionClassStudent.sectionClass.section', 'sectionClassStudent.student', 'term', 'sectionClassFee.fee', 'user'])
            ->when($request->query('section'), fn($query, $value) => $query->whereHas('sectionClassStudent.sectionClass', fn($query) => $query->where('section_id', $value)))
            ->when($request->query('section_class'), fn($query, $value) => $query->whereHas('sectionClassStudent', fn($query) => $query->where('section_class_id', $value)))
            ->when($request->query('term'), fn($query, $value) => $query->where('term_id', $value))
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('date', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('date', '<=', $value))
            ->orderBy('date', 'desc');
    }

    protected function filterLabels(Request $request)
    {
        $labels = [];

        if ($request->query('section')) {
            $labels['Section'] = optional(Section::find($request->query('section')))->name;
        }
        if ($request->query('section_class')) {
            $labels['Class'] = optional(SectionClass::find($request->query('section_class')))->name;
        }
        if ($request->query('term')) {
            $labels['Term'] = optional(Term::find($request->query('term')))->name;
        }
        if ($request->query('from_date')) {
            $labels['From Date'] = $request->query('from_date');
        }
        if ($request->query('to_date')) {
            $labels['To Date'] = $request->query('to_date');
        }

        return $labels;
    }
}
