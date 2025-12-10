# Stock Management System

A comprehensive **Stock Management System** built with **Laravel (Backend)** and **React (Frontend)**. This system allows managing products, stock levels, sales, users, roles, permissions, and generating detailed PDF reports. It is designed for small to medium-sized businesses to efficiently manage inventory and sales.

---

## Project History

This project was started as a practical implementation of a **full-stack web application** to manage inventory and sales. The main goals were:

- To learn and integrate **Laravel (backend)** and **React (frontend)**.
- Implement a **role-based access control system**.
- Build a **dynamic dashboard** with sales statistics.
- Enable generating **PDF reports** for sales and inventory.
- Support a **multi-user environment** with different roles and permissions.

---

## Features

### Authentication & User Management
- User registration and login
- Password hashing and authentication
- Role-based access control
- Permissions system:
  - **Admin:** Full access to all modules
  - **Manager:** Manage products, view sales reports
  - **Cashier:** Add sales, view personal sales
- User profile management

### Products & Stock Management
- Product CRUD (Create, Read, Update, Delete)
- Track stock quantity per product
- Assign products to categories
- Search and filter products
- Alerts for low stock

### Sales Management
- Create sales with multiple products
- Automatically update stock on sale
- View all sales in a table
- Filter sales by date, user, or mode
- Calculate total amounts and quantities
- Generate **PDF sales reports** using DomPDF

### Roles & Permissions
- Admin can create and assign roles and permissions
- Each role can have custom access to modules
- Enforces secure access to all endpoints

### Dashboard & Statistics
- Overview of products, sales, and users
- Total sales and quantity sold
- Charts for sales trends (future integration)
- Quick links to manage products, users, and generate reports

### PDF Reports
- Generate **professional PDF reports** for sales
- Includes:
  - User, products sold, quantities, total amounts, date
  - Total sales and total quantity sold
  - Date and time of report generation
- Exportable and printable

### Frontend (React)
- Dynamic, responsive interface
- Login and registration pages
- Dashboard overview with metrics
- Product and sales management pages
- PDF report download

---

## Roles & Permissions in Detail

| Role    | Permissions                                                                 |
|---------|----------------------------------------------------------------------------|
| Admin   | Manage all users, roles, permissions, products, sales, reports             |
| Manager | Manage products, view all sales, generate reports                           |
| Cashier | Add sales, view personal sales only                                         |

---

## Technologies Used

- **Backend:** Laravel, PHP, MySQL
- **Frontend:** React, Axios, Tailwind CSS
- **PDF Generation:** DomPDF
- **Version Control:** Git & GitHub
- **Environment:** XAMPP, Node.js

---

## Installation

### Backend

1. Clone the repository:

```bash
git clone https://github.com/issam-belkada/Stock-Management-System.git
cd Stock-Backend
