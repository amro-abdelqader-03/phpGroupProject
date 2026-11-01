# phpGroupProject
# 💍 Wedding Store – E-Commerce Website

**Wedding Store** is a web-based e-commerce platform designed to provide customers with an easy and convenient way to explore and purchase wedding-related products online. The platform offers an organized shopping experience where customers can browse products, explore categories, view product details, and complete their purchases.

The system also provides an administrative dashboard that enables store administrators to manage products, categories, customers, and orders while monitoring store performance and sales activities.

Built using **Object-Oriented PHP, MySQL, HTML5, CSS3, Bootstrap, and JavaScript**, Wedding Store aims to deliver a responsive, user-friendly, and secure online shopping experience.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Project Goals](#project-goals)
- [Core Features](#core-features)
- [Technology Stack](#technology-stack)
- [System Architecture](#system-architecture)
- [Database](#database)
- [Security](#security)
- [User Interface](#user-interface)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Team Members](#team-members)
- [Future Enhancements](#future-enhancements)

---

## Project Overview

Wedding Store is an online shopping platform focused on wedding-related products and shopping needs. It allows customers to discover products, browse different categories, search for specific items, and purchase products through a straightforward checkout process.

The platform includes two main user experiences:

- **Customer Experience:** Customers can create accounts, browse wedding products, manage their shopping carts, place orders, and interact with products through reviews and ratings.
- **Admin Dashboard:** Administrators can manage store data, including products, categories, users, and orders, while accessing sales statistics and performance insights.

The application follows an object-oriented PHP approach and uses MySQL as its relational database.

## Project Goals

- Develop a complete online shopping platform specialized in wedding-related products.
- Provide customers with a simple and convenient shopping experience.
- Organize wedding products into categories for easier browsing.
- Enable customers to search for products and find items that match their needs.
- Provide administrators with centralized store-management tools.
- Implement secure authentication and database operations.
- Build a responsive interface that works across different devices.

## Core Features

### Customer Features

- User registration and login using email and password.
- User profile management.
- Browse wedding-related products and categories.
- View detailed product information.
- Search and filter products by name, category, and price.
- Add products to the shopping cart.
- View, update, and remove cart items.
- Complete the checkout process and place orders.
- Submit product comments, reviews, and ratings.

### Administration Features

- Secure admin authentication and role-based access.
- Dedicated admin dashboard.
- Store performance statistics, including:
  - Total revenue.
  - Total customers.
  - Total products.
  - Total orders.
  - Monthly revenue insights.
  - Top-selling products.
  - Product revenue analytics.
  - Orders distribution by country.
  - Recent customer orders.
- Full CRUD operations for:
  - Products.
  - Categories.
  - Users.
  - Orders.
- Product reviews and ratings management.

### AI-Powered Shopping Assistant

Wedding Store's planned AI shopping assistant aims to help customers find suitable products based on their preferences and requirements.

Customers can describe what they are looking for, their intended use, preferred style, and other relevant details. The assistant can then recommend products that match their needs.

**Example request:**

> I am looking for a wedding outfit in a dark color. I prefer an elegant style and need something suitable for a formal wedding event.

The assistant is intended to make product discovery easier through personalized recommendations. Its availability depends on completing the AI integration and configuration.

### Google Authentication

The project scope also includes Google Authentication, allowing customers to register and log in using their Google accounts in addition to the traditional email-and-password authentication system.

Availability depends on completing the Google OAuth integration and configuration.

---

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP (OOP) | Server-side application logic |
| MySQL | Relational database |
| PDO | Database connectivity and secure queries |
| HTML5 | Page structure |
| CSS3 | Styling and responsive layouts |
| Bootstrap 5 | Responsive UI components |
| JavaScript | Client-side interactions |
| Chart.js | Dashboard data visualization |
| Leaflet.js | Interactive geographical order visualization |
| Git | Version control and collaboration |
| XAMPP | Local development environment |

---

## System Architecture

Wedding Store follows an object-oriented PHP architecture with an MVC-inspired structure that separates application responsibilities.

- **Models:** Handle database operations and data management.
- **Controllers:** Process requests and manage application business logic.
- **Views / Pages:** Display application content and user interfaces.
- **Configuration:** Contains database and application configuration.
- **Core:** Provides shared application utilities and response handling.

This structure improves code organization, maintainability, and reusability.

## Database

The project uses a relational **MySQL** database named `wedding_store`.

The database supports the main e-commerce operations, including customer accounts, products, categories, shopping activities, and orders.

Database relationships are designed to maintain data consistency and support the application's business requirements.

> **Database setup:** Import the SQL database file included in the project into MySQL before running the application.

## Security

Security is an important part of Wedding Store. The application follows security practices including:

- Password hashing to protect user credentials.
- Session-based authentication.
- Role-based access control for administrators and regular users.
- Server-side input validation.
- PDO prepared statements for database queries.
- Protection against SQL injection.
- Output escaping to reduce Cross-Site Scripting (XSS) risks.
- Protected admin pages and API routes.
- Secure session management.

## User Interface

Wedding Store is designed with a clean, responsive, and user-friendly interface.

The customer-facing website includes:

- Landing page with navigation and authentication links.
- Promotional slider and featured products.
- Discount sections.
- Product categories and product listings.
- Product details pages.
- Shopping cart and checkout pages.
- Customer account and profile pages.

The administration interface includes a dedicated sidebar and dashboard with navigation for managing users, categories, products, and orders.

The design aims to provide a consistent experience across desktop, tablet, and mobile devices.

## Project Structure

The project uses the following main directories:

```text
phpGroupProject/
├── admin/
│   ├── includes/
│   │   ├── header.php
│   │   └── footer.php
│   ├── dashboard.php
│   ├── users.php
│   ├── categories.php
│   ├── products.php
│   └── orders.php
│
├── app/
│   ├── config/
│   │   └── Database.php
│   ├── controllers/
│   └── models/
│
├── core/
│   └── Response.php
│
└── public/
    └── assets/
        ├── css/
        ├── js/
        └── images/
```

*This is a high-level overview of the main application directories. Additional files and folders may exist in the repository.*

## Getting Started

### Prerequisites

Make sure you have installed:

- [XAMPP](https://www.apachefriends.org/)
- [Visual Studio Code](https://code.visualstudio.com/) or another code editor.
- [Git](https://git-scm.com/)
- A modern web browser.

### Installation

**1. Clone the repository**

```bash
git clone <repository-url>
```

**2. Place the project inside the XAMPP `htdocs` directory.**

The project folder should be located at:

```text
C:\xampp\htdocs\phpGroupProject
```

**3. Start Apache and MySQL**

Open the XAMPP Control Panel and start both services.

**4. Create the database**

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a database named:

```text
wedding_store
```

**5. Import the database**

Import the SQL database file provided with the project into the `wedding_store` database.

**6. Configure the database connection**

Open:

```text
app/config/Database.php
```

Make sure the database host, name, username, and password match your local MySQL configuration.

**7. Run the application**

Open the following URL in your browser:

```text
http://localhost/phpGroupProject/
```

To access the admin dashboard:

```text
http://localhost/phpGroupProject/admin/
```

### Configuration Notes

- Make sure Apache and MySQL are running before accessing the website.
- Use the correct database credentials in the configuration file.
- Do not upload private credentials, API keys, or OAuth secrets to the public repository.
- AI Shopping Assistant and Google Authentication require their respective service configurations if enabled.

---

## Team Members

Wedding Store was developed collaboratively by:

| # | Team Member |
|---:|---|
| 1 | Ahmad |
| 2 | Dana |
| 3 | Rama |
| 4 | Amro |
| 5 | Mohammad |

---

## Future Enhancements

Potential improvements for Wedding Store include:

- Completing the AI-powered shopping assistant.
- Integrating Google OAuth authentication.
- Improving personalized product recommendations.
- Expanding product reviews and ratings functionality.
- Enhancing sales analytics and reporting.
- Improving the customer shopping experience.
- Further optimizing performance and mobile responsiveness.

---

## Acknowledgements

Wedding Store was developed as a collaborative e-commerce project, applying practical concepts in Object-Oriented PHP, MySQL database design, secure web development, responsive UI implementation, and software development teamwork.