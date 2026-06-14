<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $hasTransactions = Transaction::where('user_id', auth()->id())
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->exists();

        if (!$hasTransactions) {
            return back()->with('error', 'No transaction data found for the selected date range.');
        }

        try {
            if ($request->format === 'pdf') {
                $transactions = Transaction::where('user_id', auth()->id())
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->with(['category', 'wallet'])
                    ->orderBy('transaction_date', 'asc')
                    ->get();

                $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
                $totalExpense = $transactions->where('category.type', 'expense')->sum('amount');
                $netBalance = $totalIncome - $totalExpense;

                $pdf = Pdf::loadView('report.pdf', compact('transactions', 'startDate', 'endDate', 'totalIncome', 'totalExpense', 'netBalance'));
                
                return $pdf->download('transaction_report_' . $startDate . '_to_' . $endDate . '.pdf');
            }

            if ($request->format === 'excel') {
                return Excel::download(new TransactionsExport($startDate, $endDate), 'transaction_report_' . $startDate . '_to_' . $endDate . '.xlsx');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}
