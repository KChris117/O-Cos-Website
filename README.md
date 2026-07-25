<div align="center">
  <h1>✨ O'Cos - Premium Cosmetics E-Commerce</h1>
  <p>An exclusive and modern e-commerce platform dedicated to premium cosmetics, skincare, and beauty products.</p>
</div>

<br />

## 📖 About The Project

O'Cos was born out of a challenge during the COVID-19 pandemic. We observed that many cosmetic businesses were struggling to sell their products offline due to social restrictions. To overcome this hurdle and provide a modern solution, we built this exclusive e-commerce platform. O'Cos bridges the gap between premium cosmetic brands and beauty enthusiasts, ensuring that self-care and beauty can still thrive from the safety of home.

This project was proudly developed as a final project by **Semester 1 Informatics Engineering students from Poltek Batam**.

## 🚀 Key Features

* **Premium Responsive UI/UX**: A modern, glassmorphism-inspired design with a strict 2-layout system optimized for both Desktop and Mobile devices.
* **Product Catalog**: Dynamic product grid with built-in pagination.
* **User Authentication**: Secure Login & Sign-up system with password hashing.
* **Shopping Cart & Checkout**: Add products to cart, adjust quantities, and process simulated checkouts.
* **Favorites (Wishlist)**: Users can save their favorite products.
* **Order Tracking**: Users can monitor their order status (Pending, On Packing, On Delivery, Completed).
* **Admin Dashboard**:
  * **Overview**: Real-time statistics (Total Users, Sales, Orders).
  * **Manage Orders**: Update order statuses and track transactions.
  * **Manage Items**: Create, Read, Update, and Delete (CRUD) cosmetic products.
  * **Manage Users**: View and manage registered accounts.

## 🛠️ Tech Stack

* **Frontend**: HTML5, CSS3 (Vanilla Custom CSS), JavaScript (Vanilla)
* **Backend**: PHP 8 (Native / Procedural)
* **Database**: MySQL (MariaDB)
* **Architecture**: Server-Side Rendering (SSR) with multi-page structure.

## 👥 Meet The Team

| Name | Role | Specialization |
| :--- | :--- | :--- |
| **Christoffel Aristo Marbun** | Team Leader | Full Stack Programmer 💻 |
| **Tarissa Magdalena** | Core Backend | Backend Programmer 👩🏻‍💻 |
| **Jonathan Opel Nainggolan** | UI/UX & Code | Frontend Programmer 👨🏻‍💻 |
| **Muhammad Hasan** | UI/UX & Code | Frontend Programmer 👨🏻‍💻 |

## ⚙️ How to Run Locally

### Option 1: Using XAMPP (Standard)
1. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/o-cos-website.git
   ```
2. Move the project folder to your local server directory (e.g., `htdocs` for XAMPP).
3. Start **Apache** and **MySQL** from your XAMPP Control Panel.
4. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and create a new database named `db_o_cos`.
5. Import the `db_o_cos.sql` file provided in this repository into the database.
6. Open your browser and navigate to `http://localhost/O-Cos-Website/` (adjust the folder name if different).

### Option 2: Using Docker (Containerization)
This project is fully containerized and Docker-ready.
1. Ensure you have Docker and Docker Compose installed.
2. Open your terminal in the project directory.
3. Run the following command to build and start the containers:
   ```bash
   docker-compose up -d
   ```
4. The web application will be accessible at `http://localhost:8080`. The database is automatically initialized.

## 🔒 Default Admin Account (For Testing)
* **Email:** `offelmarbun@gmail.com`
* *(Note: Password is hashed in the database. Ensure you use the provided SQL dump to access the admin account, or create a new user and change their role to 'admin' in the database).*

---
<div align="center">
  <i>"The trusted premium cosmetics platform to beautify your day."</i>
</div>
