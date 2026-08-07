<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventorySale;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryExportController extends Controller
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

    public function viewPdf(Request $request)
    {
        $items = InventoryItem::with('category')
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('created_at', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('created_at', '<=', $value))
            ->get();

        $categories = InventoryCategory::orderBy('name')->get();

        return $this->renderPdfView('finance.inventory.exports.view', [
            'items' => $items,
            'categories' => $categories,
        ], 'inventory-overview.pdf');
    }

    public function stockPdf(Request $request)
    {
        $items = InventoryItem::with('category')
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('updated_at', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('updated_at', '<=', $value))
            ->get();

        return $this->renderPdfView('finance.inventory.exports.stock', [
            'items' => $items,
        ], 'inventory-stock.pdf');
    }

    public function categoriesPdf(Request $request)
    {
        $categories = InventoryCategory::orderBy('name')
            ->when($request->query('searchTerm'), fn($query, $value) => $query->where('name', 'like', '%'.$value.'%'))
            ->get();

        return $this->renderPdfView('finance.inventory.exports.categories', [
            'categories' => $categories,
        ], 'inventory-categories.pdf');
    }

    public function salesPdf(Request $request)
    {
        $sales = InventorySale::with(['saleItems.item.category', 'student.student'])
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('usage_date', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('usage_date', '<=', $value))
            ->when($request->query('searchTerm'), function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query->where('evidence', 'like', '%'.$value.'%')
                        ->orWhere('notes', 'like', '%'.$value.'%')
                        ->orWhereHas('student.student', fn($query) => $query->where('name', 'like', '%'.$value.'%')->orWhere('admission_no', 'like', '%'.$value.'%'))
                        ->orWhereHas('saleItems.item', fn($query) => $query->where('name', 'like', '%'.$value.'%')->orWhere('sku', 'like', '%'.$value.'%'));
                });
            })
            ->get();

        return $this->renderPdfView('finance.inventory.exports.sales', [
            'sales' => $sales,
        ], 'inventory-sales.pdf');
    }

    public function reconcilePdf(Request $request)
    {
        $transactions = InventoryTransaction::with('item.category')
            ->when($request->query('from_date'), fn($query, $value) => $query->whereDate('transaction_date', '>=', $value))
            ->when($request->query('to_date'), fn($query, $value) => $query->whereDate('transaction_date', '<=', $value))
            ->get();

        return $this->renderPdfView('finance.inventory.exports.reconcile', [
            'transactions' => $transactions,
        ], 'inventory-reconcile.pdf');
    }
}
