<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromView, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->with(['category', 'wallet'])
            ->orderBy('transaction_date', 'asc')
            ->get();

        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
        $totalExpense = $transactions->where('category.type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('report.excel', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
