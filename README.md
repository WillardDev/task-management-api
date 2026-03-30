# Task Management API

A Laravel-based REST API for managing tasks with priority levels and status tracking.

## Features

- Create tasks with title, due date, and priority
- List tasks with sorting and filtering
- Update task status with progression rules
- Delete completed tasks
- Daily task report (bonus feature)

## Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+
- Laravel 11.x

## Local Setup

### 1. Clone the Repository
```bash
git clone https://github.com/WillardDev/task-management-api.git
cd task-management-api
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Create Database
```bash
mysql -u root -p
CREATE DATABASE task_management;
exit
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. (Optional) Seed Database
```bash
php artisan db:seed
```

### 7. Start Development Server
```bash
php artisan serve
```

API will be available at: `http://localhost:8000/api/tasks`


## Frontend (Blade)

The application now includes a fully functional, premium frontend built with Laravel Blade and Tailwind CSS.

### 1. Task Board
**URL:** `/tasks`

A Kanban-style board that organizes tasks into three columns: **Pending**, **In Progress**, and **Done**. 
- Tasks are sorted by priority (High → Low) and then by due date.
- Quick action buttons allow for rapid status updates directly from the board.

### 2. Task Details
**URL:** `/tasks/{id}`

A dedicated view for each task, featuring:
- High-impact status and priority badges.
- Deadline and "Time Remaining" countdowns.
- Context-aware action buttons (Start, Complete, or Delete).

### 3. Create Task
**URL:** `/tasks/create`

A sleek, user-friendly form for adding new tasks.
- Real-time validation feedback.
- Automatic redirection to the board upon successful creation.

## Deployment (Railway)

### 1. Create Railway Account
- Go to [railway.app](https://railway.app)
- Sign up with GitHub

### 2. Create New Project
- Click "New Project"
- Select "Deploy from GitHub repo"
- Connect your repository

### 3. Add MySQL Database
- Click "New Service"
- Select "Database" → "MySQL"
- Copy connection details

### 4. Configure Environment Variables
- In your Laravel service, go to "Variables"
- Add all variables from `.env.example`
- Use MySQL connection details from Railway

### 5. Deploy
- Railway will automatically deploy on push to main branch
- Run migrations: Add build command `php artisan migrate --force`

### 6. Access Your API
- Railway will provide a public URL
- API will be at: `https://your-app.railway.app/api/tasks`

## Business Rules Implemented

Title + due_date must be unique  
Due date cannot be in the past  
Status can only progress (no skipping/reverting)  
Only 'done' tasks can be deleted  
Tasks sorted by priority (high→low) then due_date (ascending)

## Testing

Use Postman, Insomnia, or curl to test the endpoints.

**Example with curl:**
```bash
# Create task
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Test task","due_date":"2026-04-01","priority":"high"}'

# List tasks
curl http://localhost:8000/api/tasks

# Update status
curl -X PATCH http://localhost:8000/api/tasks/1/status \
  -H "Content-Type: application/json" \
  -d '{"status":"in_progress"}'

# Delete task
curl -X DELETE http://localhost:8000/api/tasks/1

# Daily report
curl http://localhost:8000/api/tasks/report?date=2026-03-30
```

## Live Demo

**API URL:** [Your deployed URL here]

## Author

Willard Owiti - Laravel Engineer Intern Candidate
