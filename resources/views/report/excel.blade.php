<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">Money Track - Transaction Report</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Generated: {{ now()->format('d M Y H:i:s') }}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6;">Date</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Description</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Wallet Name</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Category Name</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Transaction Type</th>
            <th style="font-weight: bold; background-color: #f3f4f6;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('Y-m-d') }}</td>
                <td>{{ $trx->description }}</td>
                <td>{{ $trx->wallet->name }}</td>
                <td>{{ $trx->category->name }}</td>
                <td>{{ ucfirst($trx->category->type) }}</td>
                <td>{{ $trx->amount }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="font-weight: bold;">Total Income</td>
            <td style="font-weight: bold;">{{ $totalIncome }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="font-weight: bold;">Total Expense</td>
            <td style="font-weight: bold;">{{ $totalExpense }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="font-weight: bold;">Net Balance</td>
            <td style="font-weight: bold;">{{ $netBalance }}</td>
        </tr>
    </tbody>
</table>
