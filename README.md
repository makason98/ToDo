# ToDo

A full-featured task-management web application built with Laravel. Users can organize their work with tasks, subtasks, labels, comments, and attachments across list, board, and calendar views. A dedicated admin panel gives moderators platform-wide visibility, content moderation, broadcast announcements, user feedback handling, reports, and an audit trail.

## Tech stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL / MariaDB
- **Frontend:** Blade templates + Alpine.js + Tailwind CSS v4
- **Build:** Vite

---

## User features

### Authentication
- Email + password registration with **code-based email verification** (instead of click-link tokens)
- Login, logout, "forgot password" flow, password reset
- Profile editing (name, email, password) and account deletion

### Tasks
- Create, edit, soft-delete, and toggle completion of tasks
- Per-task fields: title, description, due date, priority (low / medium / high), status, recurrence (daily / weekly / monthly / custom interval)
- **Subtasks** with their own completion state and ordering
- **Comments** with attachments
- **File attachments** at task or comment level
- **Labels** (user-owned, color-coded) attached many-to-many to tasks
- **Drag-and-drop reordering** within lists

### Multiple views of the same tasks
- **List view** — filter by Active / Completed / Overdue, by label, with full-text search
- **Board (Kanban) view** — columns by status, drag tasks between columns
- **Calendar view** — see tasks on their due dates

### Dashboard
- Personalized greeting with productivity stats: active tasks, overdue, completed today
- Day-streak counter (consecutive days with at least one completion)
- Completion summary (today / this week / this month)
- Upcoming tasks list and recent activity feed

### Activity log
- Personal feed of every action you've taken (task created, completed, edited, etc.)

### Notifications
- In-app notification bell with unread count
- Notifications for: task reminders (sent daily by scheduled job), broadcast announcements from admins, feedback status changes
- Mark individual / all as read; delete

### Feedback to the team
- Submit a **bug report**, **improvement** request, or **other** message
- Personal page lists everything you've submitted with current status
- See the admin's resolution note when status changes to Done or Rejected
- Notified in-app when an admin updates your feedback

### Other
- **Dark mode** toggle (persisted in `localStorage`)
- Fully responsive — works on mobile, tablet, desktop
- **Maintenance banner** appears as a yellow strip when admins enable it
- **"Go Admin"** shortcut button visible to admin users for quick switching to the admin panel

---

## Admin panel

Accessed via a **separate login at `/admin/login`**. Only users with an `admin_role` can sign in there. Four admin tiers, ranked highest to lowest:

| Role | What it can do |
|------|----------------|
| **Root** | Everything, including platform settings and editing other admins |
| **Super admin** | Edit admins, admins, and moderators |
| **Admin** | Edit moderators and regular users |
| **Moderator** | View-only of users (no edit) |

Hierarchy rule: you can only edit users with a **strictly lower** role than yours, and you can never edit yourself through the admin panel.

### Admin sidebar — full menu

#### Dashboard (`/admin/dashboard`)
- Platform-wide stats: total users, verified / unverified / admins counts, total / completed / active / overdue tasks
- Daily signup bar chart (last 7 days)
- Donut chart of completion rate
- Top 5 users by task count, recent signups list

#### Users (`/admin/users`)
- Searchable, paginated list (search by name / email)
- Filter by status (verified / unverified / admin) and sort by registration date
- **View** button for any admin → full user profile + activity stats
- **Edit** button (gated by hierarchy rules) → change name, email, password, verification status, admin role
- Inline-styled badges for verified / unverified / admin role

#### Tasks (`/admin/tasks`)
- All tasks across the platform with search, user filter (text-based, scales to thousands of users), status / priority / soft-deletion filters, sort options
- **Soft-delete** button — task disappears from the user's list but stays restorable
- **Restore** for trashed tasks
- Detail page shows full task + subtasks + comments + attachments + labels

#### Comments (`/admin/comments`)
- Search and moderate every comment ever posted
- Filter by author (text search), deletion state
- Soft-delete / restore buttons
- Direct links to the user and the parent task

#### Activity Log (`/admin/activity`)
- Append-only audit trail of user actions (task created, completed, comment added, etc.)
- Filter by user, action type, subject type, date range
- Read-only

#### Announcements (`/admin/announcements`)
- Send an in-app notification to everyone (or filtered audience)
- Audience options: All users / Verified / Unverified / Admins / Regular users
- Live recipient count preview before sending
- Styled confirmation modal (no native browser popup)
- History table of every broadcast — sender, audience, recipient count, when

#### Feedback (`/admin/feedback`)
- Inbox of bug reports / improvement requests submitted by users
- Status counters at the top (Open / In progress / Done / Rejected)
- Sidebar badge shows the unresolved count
- Filter defaults to **Unresolved**, with options for All / per-status
- Open a request → write a resolution note + change status; user is notified instantly

#### Reports (`/admin/reports`)
- Date-range analytics with quick shortcuts (Last 7 / Last 30 / This month) or custom from–to
- Daily bar charts: signups, tasks created, tasks completed
- 24-hour distribution of when users create tasks
- Top 5 labels and top 10 users in the selected range
- All in-range completion rate as a single number

#### Audit (`/admin/audit`)
- **Admin-only** audit trail — only entries with `action LIKE 'admin.%'`
- Tracks: user edits, task / comment soft-deletes & restores & purges, announcement sends, feedback status changes, settings updates, trash-empties
- Filter by admin (text search), action type, date range

#### Trash (`/admin/trash`)
- Unified view of all soft-deleted tasks and comments (tabbed UI)
- Per-row **Restore** or permanent **Purge** buttons
- "Empty trash" bulk action per tab (with confirmation)
- Sidebar shows total trashed count as a gray badge

#### Settings (`/admin/settings`) — **root only**
- **Registration open** toggle — when off, public `/register` shows a "Registrations are currently closed" page and the POST endpoint rejects
- **Maintenance banner** — yellow strip text shown at the top of every authenticated page (user + admin) when not blank

### Promoting a user to admin via CLI

```bash
php artisan user:make-admin you@example.com --role=root
php artisan user:make-admin foo@bar.com  --role=admin   # default
php artisan user:make-admin foo@bar.com  --revoke
```

Roles: `root`, `super_admin`, `admin`, `moderator`.

---

## Local development setup

```bash
# 1. PHP dependencies
composer install

# 2. Frontend dependencies
npm install

# 3. Environment
cp .env.example .env
php artisan key:generate
# edit .env: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Database
php artisan migrate

# 5. (Optional) seed fake users for local testing
php artisan tinker --execute="\App\Models\User::factory()->count(15)->create();"

# 6. Run
php artisan serve         # http://127.0.0.1:8000
npm run dev               # in another terminal — Vite dev server with hot reload
```

The scheduled job that sends task reminders + email alerts (`SendTaskReminders`) runs daily at 8:00 AM. To trigger it manually:

```bash
php artisan schedule:run    # runs whatever is due now
# or directly:
php artisan app:send-task-reminders
```

---

## Production deployment

Recommended steps after uploading the project zip:

```bash
composer install --no-dev --optimize-autoloader

cp .env.production .env       # edit DB creds, APP_URL, MAIL_*
php artisan key:generate      # only if APP_KEY is empty

# Import the included schema
mysql -u <user> -p <db> < database_export.sql
php artisan migrate           # no-op if dump was current

php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Make yourself admin
php artisan user:make-admin you@example.com --role=root
```

Set up cron for the scheduler:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Project structure (high level)

```
app/
├── Console/Commands/         # SendTaskReminders, MakeUserAdmin
├── Http/Controllers/         # User-side controllers
│   ├── Admin/                # Admin panel controllers
│   └── Auth/                 # Breeze auth + custom email verification
├── Http/Middleware/          # EnsureUserIsAdmin, EnsureUserIsRoot
├── Models/                   # User, Task, Comment, Label, Subtask,
│                             # Attachment, ActivityLog, InAppNotification,
│                             # Announcement, FeedbackRequest, Setting
└── View/Components/          # AppLayout, AdminLayout, GuestLayout

resources/views/
├── admin/                    # Admin panel (own layout, nav, sidebar)
├── auth/                     # Login, register, verify-code, etc.
├── feedback/                 # User feedback pages
├── tasks/                    # Tasks list, calendar, kanban, partials
├── layouts/                  # User layout + navigation
└── partials/                 # Maintenance banner, etc.

routes/
├── web.php                   # User routes
├── admin.php                 # Admin routes
├── auth.php                  # Auth routes
└── console.php               # Scheduler

database/migrations/          # 22+ migrations covering everything above
```

---

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
