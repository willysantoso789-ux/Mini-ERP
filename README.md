# Money Track (Mini-ERP)

![Money Track Logo](https://via.placeholder.com/150x150.png?text=MT)

**Money Track** is a comprehensive, professional-grade Personal Finance and Mini-ERP system built with Laravel. It helps users manage their personal or small-business finances with robust modules for tracking transactions, analyzing financial health, and setting long-term goals.

---

## 🌟 Feature List

* **🔐 Multi-User Authentication**: Complete data isolation. Every query and model is strictly scoped to the authenticated user using global scopes (`auth()->id()`).
* **💰 Wallet Management**: Manage multiple wallets (Cash, Bank Accounts, E-Wallets) with real-time balance tracking.
* **📂 Category Management**: Organize transactions by income and expense categories with intuitive icons. Includes system-protected categories for internal transfers.
* **💸 Transaction Tracking**: Record daily incomes and expenses. Attach image proofs (receipts) to transactions.
* **📈 Interactive Dashboard**: Visual representation of financial data using charts, providing a clear overview of Income vs. Expense and Category breakdowns.
* **📊 Reporting Module**: Export transaction data to professional **PDF** and **Excel** formats using date range filters.
* **🎯 Budgeting**: Set monthly budgets per category to control spending habits.
* **💭 Dream Planner**: Goal-based savings tracker to help achieve long-term financial dreams.
* **🔄 Recurring Transactions**: Automate regular payments (subscriptions, bills) using scheduled jobs.
* **🏥 Financial Health Score**: Analytical system that calculates the overall financial health based on income, savings, and expense ratios.
* **💡 Smart Insights**: Contextual financial advice dynamically generated based on current balances and spending behavior.

---

## 📸 Screenshots

| Dashboard | Transactions |
| --- | --- |
| *(Add your dashboard screenshot here)* | *(Add your transactions screenshot here)* |

| Financial Health | Reporting |
| --- | --- |
| *(Add your financial health screenshot here)* | *(Add your reporting screenshot here)* |

---

## 🛠 Technology Stack

* **Framework:** Laravel 11 (PHP 8+)
* **Database:** MySQL / MariaDB
* **Frontend:** Blade Templating, Tailwind CSS, Alpine.js
* **Charts:** Chart.js
* **PDF Generation:** Barryvdh/Laravel-DomPDF
* **Excel Generation:** Maatwebsite/Excel

---

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

4. **Storage Link:**
   ```bash
   php artisan storage:link
   ```
   *Required for transaction image attachments.*

---

## 🗄 Migration Guide

Run the migrations to build the database schema:

```bash
php artisan migrate
```

---

## 🌱 Seeder Guide (Demo Data)

To populate the application with realistic demo data (Wallets, Categories, Transactions, Budgets, and Dreams) for presentation purposes, run:

```bash
php artisan db:seed --class=DemoDataSeeder
```
*Note: This will create a demo user with the email `demo@example.com` and password `password`. The generated data provides a realistic 3-month financial history ensuring meaningful dashboard charts.*

---

## 🛡 Authentication Overview

The system utilizes Laravel's native authentication. Data isolation is strictly enforced at the database level.
Every primary model implements a `BelongsToUser` trait that automatically applies a global scope:
```php
where('user_id', auth()->id())
```
This guarantees that users can only ever access, edit, or report on their own financial data.

---

## 📊 Dashboard Overview

The Dashboard acts as the central hub. It aggregates data from all modules to display:
- Total Balance across all wallets
- Income vs Expense summary for the current month
- Visual pie charts for Expense by Category
- Quick access to recent transactions

---

## 📄 Reporting Overview

The Reporting module provides robust data export capabilities strictly scoped to the user's transactions:
- **Filters:** Start Date and End Date.
- **PDF Export:** Generates a professional, print-ready A4 document containing a summary (Total Income, Expense, Net Balance) and a detailed transaction table. Handles pagination automatically.
- **Excel Export:** Generates a clean spreadsheet with auto-sized columns and proper headers, perfect for further spreadsheet analysis.

---

## 🔮 Future Improvements

- [ ] **Multi-Currency Support:** Ability to track wallets in different currencies with real-time exchange rates.
- [ ] **Advanced Tagging:** Tagging transactions with multiple tags for granular filtering.
- [ ] **API Layer:** RESTful API for mobile application integration.
- [ ] **Collaborative Wallets:** Shared wallets for families or joint accounts (with permission levels).
