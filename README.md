# 📦 Product Management App (PHP OOP Project)

A PHP-based CRUD application designed to manage products with varying types (DVD, Book, Furniture) using **object-oriented principles**.  
Developed as part of a learning project focusing on PHP OOP, MySQL integration, and clean architecture.

---

## ⚡ Project Features

- **Single endpoint for product saving** — all product types are handled via one unified form and backend logic.
- **OOP & Polymorphism**:
  - Abstract `Product` class defines main product logic.
  - `DVD`, `Book`, and `Furniture` classes extend `Product` without conditional statements.
  - Type-specific behavior is handled via **polymorphism**, not `if/else` or `switch`.
- **Database abstraction**:
  - All MySQL operations are encapsulated within classes.
  - Properties, setters, and getters are used for both saving and displaying data.
- **Validation & Security**:
  - Server-side validation for required fields and numeric values.
  - Prevention of **XSS** using `htmlspecialchars`.
  - CSRF protection implemented for critical actions (edit, delete).
- **Front-end**:
  - Responsive UI for desktop and mobile.
  - Dynamic product specification fields based on selected category.
  - Add, Edit, Delete, and sort products.
  - Interactive selection of products with checkboxes.
- **PSR-compliant code**.

---

## 🛠️ Technologies & Tools

- **PHP** ^7.0, OOP approach, no frameworks.
- **MySQL** for data persistence.
- **HTML/CSS/JS** for the frontend (vanilla).
- **Session-based flash messages** for user feedback.
- **Composer** for autoloading (PSR-4).
- **Responsive design**

---

## 🚀 How to Run

> ⚠️ **Important:**  
> This project requires a **local web server** (e.g., Apache, Nginx), **PHP**, **MySQL** and **Composer**.

1. ### 📥 Clone the repository into the directory served by your web server
   ```bash
   git clone https://github.com/deniss87/learning-php-oop-app
   cd learning-php-oop-app
   ```
2. ### 📦 Install PHP dependencies (Composer)

   ```
   composer install
   ```

3. ### 🗄️ Create database & import schema + initial data

   Make sure to **add initial records** in the `Category` table so that product types are available.

   #### _You can **create a database and tables** with the required data by importing the **`db_schema.sql`** file_

   ```
   mysql -u root -p < db_schema.sql
   ```

4. ### 🔐 Configure environment variables including database credentials.

   ```
   cp .env.example .env
   ```

5. ### 🌐 Open the application in the browser
   http://localhost/learning-php-oop-app/public/
