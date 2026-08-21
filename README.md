# Project Management (Nexus Collaboration)

A multi-tenant team collaboration SaaS platform: organizations/teams, projects, Kanban-style tasks, meetings, and notifications. Built as a **Laravel modular monolith** backend with a **Next.js** frontend, per `Project Managment Document.docx` in the repo root.

Phase 1 covers project/task/meeting management without video. Phase 2 will add WebRTC video into the meetings module without requiring a rewrite.

## Tech stack

**Backend** (`backend/`)
- Laravel 13, PHP 8.3+
- Sanctum (token auth) + email 2FA (OTP)
- MySQL (dev/prod), SQLite in-memory for tests
- Database-backed queue (`QUEUE_CONNECTION=database`) for notification delivery
- Laravel scheduler for meeting reminders / overdue task flags

**Frontend** (`frontend/`)
- Next.js 16 (App Router, Turbopack) + React 19
- Tailwind CSS 4
- TanStack Query, Zustand, React Hook Form + Zod

## Architecture

The backend is a **modular monolith**: each business domain lives under `backend/app/Modules/{ModuleName}/` with its own layered structure —

```
Domain/           Models, Contracts, DTOs, Enums, Exceptions, ValueObjects
Application/      Services
Infrastructure/   Repositories, notifications, etc.
Presentation/      Http/{Controllers,Requests,Resources}, Routes/api.php, Policies
Providers/        {Module}ServiceProvider.php
Tests/            Unit, Feature
```

Modules built so far: **Tenancy** (organizations, members, roles/permissions), **Teams**, **Projects**, **Tasks** (+ labels, comments), **Meetings**, **Auth** (register/login/2FA/password reset), **Notifications**, **Dashboard**.

Cross-module communication is event-driven (e.g. Tasks dispatches a `TaskAssigned` event; the Notifications module listens for it) — modules don't call into each other directly, keeping the monolith's boundaries intact while still being deployable as a single app.

The frontend mirrors this with a feature-folder structure: `features/{name}/{api,hooks,components,types,schemas}/`, shared `components/{ui,layout}/`, `lib/{api,auth,organization,query,utils}/`, `stores/` (Zustand).

## Prerequisites

- PHP 8.3+ with Composer
- Node.js 20+ with npm
- MySQL 8+ (or update `DB_*` in `backend/.env` to use SQLite)
- (Optional) Redis, if you switch queue/cache/session drivers away from database

## Setup — step by step

### 1. Clone and enter the project

```bash
git clone <repo-url> project-management
cd project-management
```

### 2. Backend (Laravel API)

```bash
cd backend
composer install

cp .env.example .env
php artisan key:generate
```

Edit `.env` and point it at your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management
DB_USERNAME=root
DB_PASSWORD=
QUEUE_CONNECTION=database
```

Create the database (e.g. `mysql -u root -e "CREATE DATABASE project_management"`), then run migrations:

```bash
php artisan migrate
```

Start the API server:

```bash
php artisan serve
```

The API is now available at `http://localhost:8000/api`.

**Run the queue worker** (required — task/comment/meeting/invitation notifications are queued and won't appear until a worker processes them):

```bash
php artisan queue:work database
```

**Run the scheduler** (required for meeting reminders and overdue-task flags — `SendMeetingReminders` runs every minute, `FlagOverdueTasks` every 5 minutes):

```bash
php artisan schedule:work
```

In production, replace `schedule:work` with a cron entry running `php artisan schedule:run` every minute.

### 3. Frontend (Next.js)

In a separate terminal:

```bash
cd frontend
npm install
```

Create `.env.local`:

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

Start the dev server:

```bash
npm run dev
```

The app is now available at `http://localhost:3000`.

### 4. Log in

Register a new account from `/register`, or use `php artisan tinker` to seed a user directly against the backend if you prefer not to go through email 2FA in a fresh environment.

## Running tests

**Backend** (PHPUnit, includes each module's `Tests/Unit` and `Tests/Feature`):

```bash
cd backend
php artisan test
```

**Frontend** (Vitest):

```bash
cd frontend
npm run lint
```

## Common gotchas

- Notifications and reminders **require both** `queue:work` and `schedule:work` running — without them, jobs sit queued/scheduled but nothing is delivered.
- The frontend expects a running backend at `NEXT_PUBLIC_API_URL`; CORS/session config must allow `http://localhost:3000` if you change ports.
- Multi-tenant requests need an `X-Organization-Id` header (handled automatically by the frontend's API client once an organization is selected).
