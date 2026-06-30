# Money Track (Mini-ERP)

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-7.0-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpinejs&logoColor=white)](https://alpinejs.dev/)

**Money Track** is a comprehensive, professional-grade Personal Finance and Mini-ERP system built with Laravel. It helps users manage their personal or small-business finances with robust modules for tracking transactions, analyzing financial health, and setting long-term goals.


## 🌟 Feature List

* **🔐 Multi-User Authentication**: Complete data isolation. Every query and model is strictly scoped to the authenticated user using global scopes (`auth()->id()`).
* **💰 Wallet Management**: Manage multiple wallets (Cash, Bank Accounts, E-Wallets) with real-time balance tracking.
* **📂 Category Management**: Organize transactions by income and expense categories with intuitive icons. Includes system-protected categories for internal transfers.
* **💸 Transaction Tracking**: Record daily incomes and expenses. Securely attach receipt proofs to transactions (stored in private local storage and served via authorized routes).
* **📈 Interactive Dashboard**: Visual representation of financial data using charts, providing a clear overview of Income vs. Expense and Category breakdowns.
* **📊 Reporting Module**: Export transaction data to professional **PDF** format using date range filters.
* **🎯 Budgeting**: Set monthly budgets per category to control spending habits.
* **💭 Dream Planner**: Goal-based savings tracker to help achieve long-term financial dreams.
* **🔄 Recurring Transactions**: Automate regular payments (subscriptions, bills) using scheduled jobs.
* **🏥 Financial Health Score**: Analytical system that calculates the overall financial health based on income, savings, and expense ratios.
* **💡 Smart Insights**: Contextual financial advice dynamically generated based on current balances and spending behavior.


## 🛠 Technology Stack

* **Framework:** Laravel 12.0 (PHP 8.2+)
* **Database:** MySQL / MariaDB
* **Frontend:** Blade Templating, Tailwind CSS 4.0, Alpine.js, Vite 7.0
* **Charts:** Chart.js
* **PDF Generation:** Barryvdh/Laravel-DomPDF 3.x


## 🚀 Installation Guide

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/mini-erp.git
   cd mini-erp
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your `.env` file with the correct database credentials.*

4. **Start the application and background workers:**
   To run the application locally with full support for recurring transactions, open two terminals and run:
   ```bash
   php artisan serve
   ```
   ```bash
   php artisan schedule:work
   ```





## 🗄 Migration Guide

Run the migrations to build the database schema:

```bash
php artisan migrate
```


## 🌱 Seeder Guide (Demo Data)

To populate the application with realistic demo data (Wallets, Categories, Transactions, Budgets, and Dreams) for presentation purposes, run:

```bash
php artisan db:seed --class=DemoDataSeeder
```
*Note: This will create a demo user with the email `demo@example.com` and password `password`. The generated data provides a realistic 12-month (1 year) financial history ensuring meaningful dashboard charts and budget tracking.*


## 🛡 Authentication Overview

The system utilizes Laravel's native authentication. Data isolation is strictly enforced at the database level.
Every primary model implements a `BelongsToUser` trait that automatically applies a global scope:
```php
where('user_id', auth()->id())
```
This guarantees that users can only ever access, edit, or report on their own financial data.


## 📊 Dashboard Overview

The Dashboard acts as the central hub. It aggregates data from all modules to display:
- Total Balance across all wallets
- Income vs Expense summary for the current month
- Visual pie charts for Expense by Category
- Quick access to recent transactions


## 📄 Reporting Overview

The Reporting module provides robust data export capabilities strictly scoped to the user's transactions:
- **Filters:** Start Date and End Date.
- **PDF Export:** Generates a professional, print-ready A4 document containing a summary (Total Income, Expense, Net Balance) and a detailed transaction table. Handles pagination automatically.


## 🔮 Future Improvements

- [ ] **Multi-Currency Support:** Ability to track wallets in different currencies with real-time exchange rates.
- [ ] **Advanced Tagging:** Tagging transactions with multiple tags for granular filtering.
- [ ] **API Layer:** RESTful API for mobile application integration.
- [ ] **Collaborative Wallets:** Shared wallets for families or joint accounts (with permission levels).


## 📜 License

The Mini-ERP Money Track system is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
