<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-recurring-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process due recurring transactions safely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dueTransactions = RecurringTransaction::with(['category', 'wallet'])
            ->whereDate('next_processing_date', '<=', now())
            ->get();

        foreach ($dueTransactions as $rt) {
            try {
                // If it's an expense, check wallet balance
                if ($rt->category->type === 'expense') {
                    if ($rt->amount > $rt->wallet->balance) {
                        Log::warning("Recurring transaction {$rt->id} skipped due to insufficient balance in wallet {$rt->wallet_id}");
                        continue; // Skip this one
                    }
                }

                // Create the transaction
                Transaction::create([
                    'wallet_id' => $rt->wallet_id,
                    'category_id' => $rt->category_id,
                    'amount' => $rt->amount,
                    'description' => $rt->description ?? 'Recurring: ' . $rt->category->name,
                    'transaction_date' => now(),
                ]);

                // Update recurring transaction dates
                $rt->last_processed_at = now();
                
                $nextDate = \Carbon\Carbon::parse($rt->next_processing_date);
                if ($rt->frequency === 'daily') {
                    $nextDate->addDay();
                } elseif ($rt->frequency === 'weekly') {
                    $nextDate->addWeek();
                } elseif ($rt->frequency === 'monthly') {
                    $nextDate->addMonth();
                }
                
                // If the next date is STILL in the past (e.g. system was off for months), 
                // we catch it up to the future to avoid spamming transactions.
                while ($nextDate->isPast()) {
                    if ($rt->frequency === 'daily') $nextDate->addDay();
                    elseif ($rt->frequency === 'weekly') $nextDate->addWeek();
                    elseif ($rt->frequency === 'monthly') $nextDate->addMonth();
                }

                $rt->next_processing_date = $nextDate;
                $rt->save();

                $this->info("Processed recurring transaction {$rt->id}");
            } catch (\Exception $e) {
                Log::error("Failed to process recurring transaction {$rt->id}: " . $e->getMessage());
            }
        }
    }
}
