# Food Park - Restaurant E-Commerce Platform

A modern full-stack e-commerce platform for food ordering and restaurant management.

## Features

-   **User Management**: Customer accounts with order history and saved preferences
-   **Restaurant Dashboard**: Manage menu items, orders, and delivery status
-   **Shopping Cart**: Add items to cart with real-time updates
-   **Order Management**: Track orders from placement to delivery
-   **Payment Integration**: Secure payment processing
-   **Real-time Notifications**: Order status updates, Messaging
-   **Product reviews and ratings**
-   **Blog system with comments and user engagement**
-   **Admin Panel**: Manage restaurants, users, and platform settings

## Tech Stack

-   **Backend**: Laravel(PHP)
-   **Frontend**: Html, blade, css, bootstrap, javascript, jQuery
-   **Database**: MySQL
-   **Authentication**: Session-based
-   **Payment gateways**: Stripe, Razorpay, PayPal
-   **Real-time**: Pusher WebSockets

## Installation

1. Clone the repository:

```bash
git clone https://github.com/RichmanLoveday/food_and_resturant_ecommerce.git
```

2. Install dependencies:

```bash
composer install
```

3. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations:

```bash
php artisan migrate
```

5. Start the development server:

```bash
php artisan serve
```

## Getting Started

Visit `http://localhost:8000` to access the application.

## Contributing

Contributions are welcome! Please read our contribution guidelines before submitting pull requests.

<!--
## License

This project is open-sourced software licensed under the MIT license. -->
