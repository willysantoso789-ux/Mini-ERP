<?php

namespace App\Exports;

use Maatwebsite\Excel\Facades\Excel;

class TransactionsExport
{
    protected $transactions;
    protected $totalIncome;
    protected $totalExpense;
    protected $netBalance;

    public function __construct(
        $transactions,
        $totalIncome,
        $totalExpense,
        $netBalance
    ) {
        $this->transactions = $transactions;
        $this->totalIncome = $totalIncome;
        $this->totalExpense = $totalExpense;
        $this->netBalance = $netBalance;
    }

    public function download()
    {
        $transactions = $this->transactions;
        $totalIncome = $this->totalIncome;
        $totalExpense = $this->totalExpense;
        $netBalance = $this->netBalance;

        $data = [];

        foreach ($transactions as $trx) {

            $data[] = [
                'Date' => $trx->transaction_date,
                'Description' => $trx->description,
                'Wallet' => $trx->wallet?->name,
                'Category' => $trx->category?->name,
                'Type' => ucfirst($trx->category?->type ?? '-'),
                'Amount' => $trx->amount,
            ];
        }

        $data[] = [];

        $data[] = [
            'Total Income',
            $totalIncome
        ];

        $data[] = [
            'Total Expense',
            $totalExpense
        ];

        $data[] = [
            'Net Balance',
            $netBalance
        ];

        return Excel::create(
            'transaction-report-' . now()->format('Ymd-His'),
            function ($excel) use ($data) {

                $excel->sheet(
                    'Transactions',
                    function ($sheet) use ($data) {

                        $sheet->fromArray($data);

                        $sheet->setAutoSize(true);
                    }
                );
            }
        )->download('xlsx');
    }
}
