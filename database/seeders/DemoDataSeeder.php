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
        $cashWallet = Wallet::create(['user_id' => $user->id, 'name' => 'Cash', 'balance' => 3500000]);
        $bankWallet = Wallet::create(['user_id' => $user->id, 'name' => 'BCA Account', 'balance' => 28000000]);
        $savingsWallet = Wallet::create(['user_id' => $user->id, 'name' => 'Savings', 'balance' => 25000000]);

        // 2. Categories
        // Income
        $salaryCat = Category::create(['user_id' => $user->id, 'name' => 'Salary', 'type' => 'income', 'icon' => 'fas fa-money-bill', 'is_system' => false]);
        $bonusCat = Category::create(['user_id' => $user->id, 'name' => 'Bonus', 'type' => 'income', 'icon' => 'fas fa-gift', 'is_system' => false]);
        $transferInCat = Category::create(['user_id' => $user->id, 'name' => 'Transfer In', 'type' => 'income', 'icon' => 'fas fa-arrow-down', 'is_system' => true]);

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
            'target_date' => Carbon::create(2026, 12, 1)->format('Y-m-d'),
        ]);
        $japanDream = Dream::create([
            'user_id' => $user->id,
            'name' => 'Japan Holiday',
            'target_amount' => 35000000,
            'target_date' => Carbon::create(2027, 4, 15)->format('Y-m-d'),
        ]);

        // 3. Transactions
        $startDate = Carbon::create(2026, 4, 1);
        $endDate = Carbon::create(2026, 7, 2);
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
                    'amount' => 1500000,
                    'description' => 'ATM Withdrawal',
                    'transaction_date' => clone $currentDate,
                ]);
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $cashWallet->id,
                    'category_id' => $transferInCat->id,
                    'amount' => 1500000,
                    'description' => 'ATM Withdrawal',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Daily Expenses (Food, Transport)
            if (rand(1, 10) > 3) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => rand(1, 10) > 5 ? $cashWallet->id : $bankWallet->id,
                    'category_id' => $foodCat->id,
                    'amount' => rand(30000, 150000),
                    'description' => 'Lunch/Dinner',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            if (rand(1, 10) > 5) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $cashWallet->id,
                    'category_id' => $transportCat->id,
                    'amount' => rand(20000, 50000),
                    'description' => 'Transport',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            // Weekend Expenses (Shopping, Entertainment)
            if ($currentDate->isWeekend() && rand(1, 10) > 6) {
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $bankWallet->id,
                    'category_id' => rand(1, 2) == 1 ? $shoppingCat->id : $entertainmentCat->id,
                    'amount' => rand(200000, 800000),
                    'description' => 'Weekend Outing',
                    'transaction_date' => clone $currentDate,
                ]);
            }

            $currentDate->addDay();
        }

        // 4. Budgets (For June and July)
        $monthsToBudget = ['2026-06', '2026-07'];
        foreach ($monthsToBudget as $monthStr) {
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $foodCat->id,
                'amount' => 3500000,
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
        }

        // 6. Recurring Transactions
        RecurringTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $bankWallet->id,
            'category_id' => $billsCat->id,
            'amount' => 180000,
            'description' => 'Netflix & Spotify',
            'type' => 'expense',
            'frequency' => 'monthly',
            'next_date' => Carbon::create(2026, 7, 5)->format('Y-m-d'),
        ]);

        RecurringTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $bankWallet->id,
            'category_id' => $billsCat->id,
            'amount' => 350000,
            'description' => 'Gym Membership',
            'type' => 'expense',
            'frequency' => 'monthly',
            'next_date' => Carbon::create(2026, 7, 10)->format('Y-m-d'),
        ]);
    }
}
