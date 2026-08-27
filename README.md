# MagixBit24 (Bitrix24 Clone with AI)

**MagixBit24** is a modern, responsive, and full-featured CRM & ERP system built with Laravel, Livewire, and Alpine.js. It aims to provide businesses with a centralized platform for managing deals, tasks, employees, contacts, and cloud storage, integrated with AI capabilities and Telegram notifications.

---

## 🚀 Features

- **Multi-Tenant Architecture**: Supports multiple companies, each with their isolated workspace and users.
- **Advanced CRM (Deals Kanban)**: Interactive Kanban board for managing sales pipelines and deals.
- **Task Management**: Comprehensive task planner with lists and Kanban views to track team progress.
- **Employee & Roles Management**: Robust RBAC (Role-Based Access Control) using Spatie Permissions to manage managers, admins, and standard employees.
- **External Contacts Manager**: Custom dynamic fields support for storing robust information about leads and clients.
- **Storage System**: Centralized file manager with Telegram Private Group integration to store files natively.
- **Activity & History Tracking**: Detailed logging of all actions (creates, updates, deletes) across the platform.
- **Localization (i18n)**: Fully supported bilingual interface (English & Uzbek), automatically saved to user profiles.
- **Telegram Authentication**: Secure and seamless login utilizing Telegram bots.

---

## 🛠 Tech Stack

- **Backend:** Laravel 11.x, PHP 8.4, PostgreSQL
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS (Glassmorphism design)
- **Authentication:** Custom Telegram Bot Login
- **Database:** PostgreSQL
- **Roles & Permissions:** Spatie Laravel Permission

---

## 💻 Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL
- Telegram Bot Token (from BotFather)

---

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Xojiakmal/MagixBit24_PHP_withAi.git
   cd MagixBit24_PHP_withAi
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies and build assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration:**
   Copy the example `.env` file and set up your local configuration.
   ```bash
   cp .env.example .env
   ```
   *Make sure to configure your database settings, Telegram credentials, and application URL in the `.env` file.*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the local server:**
   ```bash
   php artisan serve
   ```

---

## 📱 Telegram Integration Setup

For authentication and storage management, you will need a Telegram bot.
1. Open [@BotFather](https://t.me/BotFather) on Telegram and create a new bot.
2. Copy the `API Token` and Bot `Username`.
3. Add them to your `.env` file:
   ```env
   TELEGRAM_BOT_USERNAME=your_bot_username
   TELEGRAM_BOT_TOKEN=your_bot_token
   ```
4. Configure your Telegram Bot Webhook to point to `https://your-domain.com/telegram/webhook`.

---

## 🌍 Localization (En / Uz)

The application fully supports English and Uzbek languages. Users can change their preferred language via the **Profile Settings** or the **Sidebar**. The selected language is stored in the `users` table and applied universally via the `LocalizationMiddleware`.

- Language files are located in: `lang/en.json` and `lang/uz.json`

---

## 🛡 Security & Permissions

We utilize `spatie/laravel-permission` for robust security.
Standard permissions include:
- `view_dashboard`, `view_crm`, `create_deal`, `view_tasks`, `manage_employees`, `view_storage`, etc.

Run the following command to ensure all base permissions are seeded:
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```
*(Or navigate to `/seed-permissions` if configured).*

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
