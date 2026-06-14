<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #4338ca; }
        .header p { margin: 5px 0; color: #666; }
        .summary { display: table; width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box { display: table-cell; width: 33.33%; text-align: center; border: 1px solid #ddd; padding: 10px; background: #f9fafb; }
        .summary-box h3 { margin: 0 0 5px 0; font-size: 14px; color: #555; }
        .summary-box p { margin: 0; font-size: 16px; font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data th { background-color: #f3f4f6; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Money Track</h1>
        <p>Transaction Report</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Generated: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Income</h3>
            <p class="text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Expense</h3>
            <p class="text-red">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Net Balance</h3>
            <p class="{{ $netBalance >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($netBalance, 0, ',', '.') }}</p>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Wallet</th>
                <th>Category</th>
                <th>Type</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}</td>
                <td>{{ $trx->description }}</td>
                <td>{{ $trx->wallet->name }}</td>
                <td>{{ $trx->category->name }}</td>
                <td>{{ ucfirst($trx->category->type) }}</td>
                <td class="text-right {{ $trx->category->type == 'income' ? 'text-green' : 'text-red' }}">
                    {{ $trx->category->type == 'income' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
