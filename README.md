# Mini Issue Tracker (Laravel 13)

A lightweight issue tracking system built with Laravel 13, Blade, and vanilla JavaScript (AJAX).  
It allows teams to manage projects, issues, tags, members, and comments in a structured workflow.

---

## Features

### Projects

- Create, edit, delete projects
- Each project belongs to a user (owner)
- Projects contain multiple issues
- Start date & deadline support

---

### Issues

- Full CRUD system
- Belongs to a project
- Status tracking:
    - Open
    - In Progress
    - Closed
- Priority levels:
    - Low
    - Medium
    - High
- Due date support
- Filter issues by:
    - Status
    - Priority
    - Tags
- Search support (title & description)

---

### Tags (AJAX)

- Create and manage tags
- Attach / detach tags to issues without page reload
- Many-to-many relationship with issues

---

### Members (Bonus Feature)

- Assign multiple users to an issue
- Many-to-many relationship (`issue_user`)
- Attach / detach members via AJAX
- Display assigned users on issue detail page

---

### Comments (AJAX + Pagination)

- Add comments without page reload
- Load comments asynchronously
- Paginated comment loading
- New comments are prepended dynamically

---

## Tech Stack

- Laravel 13
- Blade Templates
- Vanilla JavaScript (no frontend framework)
- MySQL
- Eloquent ORM
- AJAX (Fetch API)
- PHP 8.1+ Enums

---

## Database Structure

### Main Entities

- **User**
- **Project**
    - user_id (owner)
    - name
    - description
    - start_date
    - deadline

- **Issue**
    - project_id
    - title
    - description
    - status (enum)
    - priority (enum)
    - due_date

- **Tag**
    - name (unique)
    - color (nullable)

- **Comment**
    - issue_id
    - author_name
    - body

---

### Pivot Tables

- `issue_tag` → issues ↔ tags
- `issue_user` → issues ↔ users (members)

---

## Authorization

- Policies used for project ownership
- Only project owner can:
    - Edit project
    - Delete project

---

## AJAX Features

- Tag attach/detach without reload
- Member assignment without reload
- Comment creation without reload
- Comment pagination (load more)
- Dynamic UI updates

---

## Seeding

The project includes realistic seed data:

- 5 users
- 3 projects per user
- 15 issues
- Multiple tags per issue
- Multiple members per issue
- 2–6 comments per issue

Run:

```bash
php artisan migrate:fresh --seed
```

## Design Decisions & Future Improvements

### Blade Components

For this version, Blade components were intentionally not heavily used in order to keep the structure simple and focus on backend logic, relationships, and AJAX interactions.

In a more advanced iteration, reusable Blade components could be introduced for:

- Issue cards
- Tag elements
- Modals (tags / members)
- Form inputs and validation states

This would reduce duplication and improve UI consistency across the application.
