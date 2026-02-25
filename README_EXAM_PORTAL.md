# Online Examination & Student Management Portal

A Laravel + **Livewire** portal for online examinations and student management with two roles: **Lecturer** and **Student**.

## Features

- **Roles**: Lecturer and Student (chosen at registration).
- **Authentication**: Login/register with credentials (Laravel UI).
- **Exam creation (Lecturer)**: Create exams with **multiple-choice** and **open-text** questions, per subject.
- **Class management**: Create classes; assign **students** and **subjects** to each class.
- **Subject management**: Create subjects and link them to classes.
- **Access control**: Students only see and take exams that are **assigned to their class**.
- **Time limit**: Each exam has a configurable time limit (e.g. 15 minutes); timer runs in the browser and auto-submits when time is up.
- **Results**: Students can view their submitted exam scores; multiple-choice is auto-marked, open-text is stored for manual review (score can be updated later if needed).

## Tech stack

- Laravel 12
- Livewire 4 (all main UI is Livewire)
- Laravel UI (Bootstrap, auth scaffolding)
- SQLite/MySQL (via `.env`)

## Setup

1. Install dependencies and env:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. Configure database in `.env` (e.g. `DB_CONNECTION=sqlite` and ensure `database/database.sqlite` exists, or use MySQL).

3. Run migrations and seed demo data:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

4. (Optional) Build frontend:
   ```bash
   npm install && npm run build
   ```

5. Start the app:
   ```bash
   php artisan serve
   ```

## Demo accounts (after seeding)

| Role    | Email               | Password |
|---------|---------------------|----------|
| Lecturer| lecturer@example.com| password |
| Student | student@example.com | password |

## Routes (after login)

- **Lecturer**: `/lecturer/dashboard`, `/lecturer/classes`, `/lecturer/subjects`, `/lecturer/exams`, create/edit exam, manage class (assign students & subjects), assign exam to classes.
- **Student**: `/student/dashboard`, `/student/exams` (list and take), `/student/exams/{id}/take` (with timer), `/student/results`.

## Livewire components

- **Lecturer**: `LecturerDashboard`, `ClassList`, `ClassManage`, `SubjectList`, `ExamList`, `ExamForm`, `ExamClassAssign`.
- **Student**: `StudentDashboard`, `StudentExamList`, `TakeExam` (with Alpine.js timer), `StudentResults`.

## Notes

- One attempt per student per exam; after submit they see the result and can view it under Results.
- Open-text answers are saved; marking and score updates can be added later (e.g. lecturer view of attempts and manual score).
