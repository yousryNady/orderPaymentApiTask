# Order Payment API 🛒💳

A **Laravel API** for managing orders and payments, built to support multiple payment gateways with an extensible architecture.

---

## Table of Contents

- [Requirements](#requirements)
- [Download & Installation](#download--installation)
- [Environment Setup](#environment-setup)
- [Database Setup](#database-setup)
- [Run Migrations & Seeders](#run-migrations--seeders)
- [Running the Application](#running-the-application)
- [Testing](#testing)
- [Payment Gateway Extensibility](#payment-gateway-extensibility)
- [API Documentation](#api-documentation)
- [Notes](#notes)

---

## Requirements

To run this application locally, you'll need the following installed:

* **PHP** $\ge$ 8.1
* **Composer** (for dependency management)
* **SQLite** (used by default, but supports MySQL/PostgreSQL)
* **Git**

---

## Download & Installation

Follow these steps to download and set up the project dependencies:

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/yousryNady/orderPaymentApiTask.git](https://github.com/yousryNady/orderPaymentApiTask.git)
    cd OrderPaymentApiTask
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

---

## Environment Setup

The application uses an environment file (`.env`) for configuration.

1.  **Copy the example environment file:**
    ```bash
    cp .env.example .env
    ```

2.  **Set Core Variables:** Open the newly created `.env` file and verify or update the following core settings (These are the suggested local settings):
    ```ini
    APP_NAME="OrderPaymentAPI"
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost:8000
    ```

3.  **Generate Application Key:** This key is crucial for session and data encryption.
    ```bash
    php artisan key:generate
    ```

4.  **Generate JWT Secret:** This API uses **JSON Web Tokens (JWT)** for authentication.
    ```bash
    php artisan jwt:secret
    ```

---

## Database Setup

This project is configured to use **SQLite** by default.

1.  **Configure SQLite:** Ensure the following lines are uncommented and set correctly in your `.env` file:
    ```ini
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    ```

2.  **Create the SQLite database file:**
    ```bash
    touch database/database.sqlite
    ```

---

## Run Migrations & Seeders

Apply the database schema and populate it with initial data (e.g., test users, initial payment methods).

1.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

2.  **Run Seeders:**
    ```bash
    php artisan db:seed
    ```

---

## Running the Application

Start the local Laravel development server:

```bash
php artisan serve