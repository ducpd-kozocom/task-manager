<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Task Management Web Application

This project is a web application built with Laravel that allows users to manage tasks effectively. The application focuses on user registration, login, task creation, assignment, and tracking task statuses. It also includes email notifications for task completion.

## Features

- **User Management**: Users can register, log in, and manage their accounts.
- **Task Management**:
  - Create, edit, and delete tasks.
  - Assign tasks to other users.
  - Track task statuses: `pending`, `in-progress`, `completed`.
  - Set due dates for tasks.
- **Task Viewing**: Users can view the list of tasks assigned to them.
- **Email Notifications**: Sends email notifications when tasks are assigned or completed.

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd laravel_api
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up the environment:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database and email configuration.

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations to set up the database:
   ```bash
   php artisan migrate
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

- Access the application at `http://localhost:8000`.
- Register a new user or log in with an existing account.
- Create and manage tasks from the dashboard.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   t a s k - m a n a g e r  
 