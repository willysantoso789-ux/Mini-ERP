<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Dream;
use App\Models\RecurringTransaction;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo User', 'password' => Hash::make('password')]
        );

        // Delete existing demo data for this user to avoid duplicates if run multiple times
        Wallet::where('user_id', $user->id)->delete();
        Category::where('user_id', $user->id)->delete();
        Transaction::where('user_id', $user->id)->delete();
        Budget::where('user_id', $user->id)->delete();
        Dream::where('user_id', $user->id)->delete();
        RecurringTransaction::where('user_id', $user->id)->delete();

        // 1. Wallets
        $cashWallet = Wallet::create(['user_id' => $user->id, 'name' => 'Cash', 'type' => 'cash']);
        $bankWallet = Wallet::create(['user_id' => $user->id, 'name' => 'BCA Account', 'type' => 'bank']);
        $savingsWallet = Wallet::create(['user_id' => $user->id, 'name' => 'Savings', 'type' => 'bank']);

        // 2. Categories
        // Income
        $initialBalanceCat = Category::create(['user_id' => $user->id, 'name' => 'Initial Balance', 'type' => 'income', 'icon' => '💰', 'is_system' => true]);
        $salaryCat = Category::create(['user_id' => $user->id, 'name' => 'Salary', 'type' => 'income', 'icon' => 'fas fa-money-bill', 'is_system' => false]);
        $bonusCat = Category::create(['user_id' => $user->id, 'name' => 'Bonus', 'type' => 'income', 'icon' => 'fas fa-gift', 'is_system' => false]);
        $transferInCat = Category::create(['user_id' => $user->id, 'name' => 'Transfer In', 'type' => 'income', 'icon' => 'fas fa-arrow-down', 'is_system' => true]);

        // Add Initial Balances
        Transaction::create(['user_id' => $user->id, 'wallet_id' => $cashWallet->id, 'category_id' => $initialBalanceCat->id, 'amount' => 3500000, 'description' => 'Initial Balance', 'transaction_date' => Carbon::create(2026, 1, 1)]);
        Transaction::create(['user_id' => $user->id, 'wallet_id' => $bankWallet->id, 'category_id' => $initialBalanceCat->id, 'amount' => 28000000, 'description' => 'Initial Balance', 'transaction_date' => Carbon::create(2026, 1, 1)]);
        Transaction::create(['user_id' => $user->id, 'wallet_id' => $savingsWallet->id, 'category_id' => $initialBalanceCat->id, 'amount' => 25000000, 'description' => 'Initial Balance', 'transaction_date' => Carbon::create(2026, 1, 1)]);

        // Expense
        $foodCat = Category::create(['user_id' => $user->id, 'name' => 'Food & Dining', 'type' => 'expense', 'icon' => 'fas fa-utensils', 'is_system' => false]);
        $transportCat = Category::create(['user_id' => $user->id, 'name' => 'Transportation', 'type' => 'expense', 'icon' => 'fas fa-car', 'is_system' => false]);
        $shoppingCat = Category::create(['user_id' => $user->id, 'name' => 'Shopping', 'type' => 'expense', 'icon' => 'fas fa-shopping-bag', 'is_system' => false]);
        $billsCat = Category::create(['user_id' => $user->id, 'name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => 'fas fa-file-invoice', 'is_system' => false]);
        $entertainmentCat = Category::create(['user_id' => $user->id, 'name' => 'Entertainment', 'type' => 'expense', 'icon' => 'fas fa-film', 'is_system' => false]);
        $transferOutCat = Category::create(['user_id' => $user->id, 'name' => 'Transfer Out', 'type' => 'expense', 'icon' => 'fas fa-arrow-up', 'is_system' => true]);
        $dreamSavingCat = Category::create(['user_id' => $user->id, 'name' => 'Dream Saving', 'type' => 'expense', 'icon' => 'fas fa-star', 'is_system' => true]);

        // 5. Dream Planner (Create these first so we can seed transactions for them)
        $laptopDream = Dream::create([
            'user_id' => $user->id,
            'name' => 'New Laptop',
            'target_amount' => 20000000,
            'deadline' => Carbon::create(2026, 12, 1)->format('Y-m-d'),
        ]);
        $japanDream = Dream::create([
            'user_id' => $user->id,
            'name' => 'Japan Holiday',
            'target_amount' => 35000000,
            'deadline' => Carbon::create(2027, 4, 15)->format('Y-m-d'),
        ]);

        // 3. Transactions
        $startDate = Carbon::create(2026, 1, 1); // Start from January to have plenty of historical data
        $endDate = Carbon::create(2026, 12, 31); // Up to end of year
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            // Salary per month
            if ($currentDate->day == 25) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $salaryCat->id,
                    'amount' => 18000000,
                    'description' => 'Monthly Salary',
                    'transaction_date' => clone $currentDate,
                ]);

                // Also deposit to dreams exactly on payday
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $dreamSavingCat->id,
                    'dream_id' => $laptopDream->id,
                    'amount' => 1500000,
                    'description' => 'Saving for ' . $laptopDream->name,
                    'transaction_date' => clone $currentDate,
                ]);

                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $dreamSavingCat->id,
                    'dream_id' => $japanDream->id,
                    'amount' => 2500000,
                    'description' => 'Saving for ' . $japanDream->name,
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Quarterly Bonus
            if (in_array($currentDate->month, [3, 6, 9, 12]) && $currentDate->day == 10) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $bonusCat->id,
                    'amount' => 5000000,
                    'description' => 'Quarterly Performance Bonus',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Rent / Bills
            if ($currentDate->day == 1) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $billsCat->id,
                    'amount' => 4500000,
                    'description' => 'Apartment Rent',
                    'transaction_date' => clone $currentDate,
                ]);
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $billsCat->id,
                    'amount' => 600000,
                    'description' => 'Internet & Electricity',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Mid-month Bank to Cash transfer
            if ($currentDate->day == 15) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => $transferOutCat->id,
                    'amount' => 2000000,
                    'description' => 'ATM Withdrawal',
                    'transaction_date' => clone $currentDate,
                ]);
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $cashWallet->id,
                    'category_id' => $transferInCat->id,
                    'amount' => 2000000,
                    'description' => 'ATM Withdrawal',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Daily Expenses (Food, Transport)
            if (rand(1, 10) > 2) { // 80% chance every day
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => rand(1, 10) > 5 ? $cashWallet->id : $bankWallet->id,
                    'category_id' => $foodCat->id,
                    'amount' => rand(30000, 200000),
                    'description' => 'Meals/Groceries',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            if (rand(1, 10) > 4) { // 60% chance every day
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $cashWallet->id,
                    'category_id' => $transportCat->id,
                    'amount' => rand(20000, 80000),
                    'description' => 'Transport/Gas',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Weekend Expenses (Shopping, Entertainment)
            if ($currentDate->isWeekend() && rand(1, 10) > 4) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => rand(1, 2) == 1 ? $shoppingCat->id : $entertainmentCat->id,
                    'amount' => rand(150000, 1000000),
                    'description' => 'Weekend Outing/Shopping',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            $currentDate->addDay();
        }

        // 4. Budgets (For the entire year)
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = '2026-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $foodCat->id,
                'amount' => 4000000,
                'month' => $monthStr,
            ]);
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $shoppingCat->id,
                'amount' => 2000000,
                'month' => $monthStr,
            ]);
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $entertainmentCat->id,
                'amount' => 1500000,
                'month' => $monthStr,
            ]);
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $transportCat->id,
                'amount' => 1000000,
                'month' => $monthStr,
            ]);
        }

        // 6. Recurring Transactions
        RecurringTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $bankWallet->id,
            'category_id' => $billsCat->id,
            'amount' => 180000,
            'description' => 'Netflix & Spotify',
            'frequency' => 'monthly',
            'next_processing_date' => Carbon::create(2026, 7, 5)->format('Y-m-d'),
        ]);

        RecurringTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $bankWallet->id,
            'category_id' => $billsCat->id,
            'amount' => 350000,
            'description' => 'Gym Membership',
            'frequency' => 'monthly',
            'next_processing_date' => Carbon::create(2026, 7, 10)->format('Y-m-d'),
        ]);
    }
}
