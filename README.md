# Alumni Association Website (Laravel 11 + Filament v3)

Production-ready alumni association platform with a responsive frontend and dynamic CMS-style admin panel.

## Stack

- Laravel 11
- FilamentPHP v3 (Admin Panel)
- Spatie Laravel Permission
- Spatie Laravel Activitylog
- MySQL
- TailwindCSS-based UI

## Implemented Modules

- Home: editable welcome/about/vision/president sections
- About Us: history, governing body, office bearers, chapters, bylaws
- Alumni Directory: searchable, filterable, paginated, distinguished alumni
- Events: upcoming/past, annual meet type, event gallery (photos/videos)
- News & Updates: announcements, newsletters, achievements
- Registration: external portal redirect + configurable registration links
- Contributions: donation campaigns, scholarships, endowment-style campaigns
- Career & Networking: jobs, mentorship programs, internship-ready listing model
- Gallery: albums + photo/video media
- Contact Us: dynamic contact/social details + secure contact form (validation + throttling)

## Filament Resources

- Admin Users
- Roles & Permissions
- Activity Log
- Alumni
- Event
- NewsPost (shown as News & Updates)
- GalleryAlbum (shown as Gallery)
- CommitteeMember
- Chapter
- Scholarship
- JobPosting (shown as Jobs)
- MentorshipProgram
- DonationCampaign (shown as Donations)
- StaticPage
- BylawDocument
- SiteSetting
- ContactMessage

## Setup

1. Install dependencies:
   ```bash
   composer install
   ```
2. Configure `.env` for MySQL (already set to MySQL keys in this project).
3. Generate app key:
   ```bash
   php artisan key:generate
   ```
4. Create storage symlink for uploads:
   ```bash
   php artisan storage:link
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Seed default roles and demo admin users:
   ```bash
   php artisan db:seed
   ```
7. Start server:
   ```bash
   php artisan serve
   ```

Frontend: `http://127.0.0.1:8000`  
Admin panel: `http://127.0.0.1:8000/admin`

## Demo Admin Accounts

All seeded demo users use password: `Password@123`

- Super Admin: `superadmin@example.com`
- Events Manager: `events@example.com`
- Gallery Manager: `gallery@example.com`
- News Manager: `news@example.com`
- Faculty Manager: `faculty@example.com`

## Roles & Access Flow

- Super Admin has every permission and can manage users, roles, permissions, all content modules, dashboard widgets, and activity logs.
- Section managers only receive `view/create/update/delete` permissions for their assigned module.
- Filament resources extend a shared authorized resource class, so navigation, list/create/edit/delete actions, and bulk deletes are hidden or blocked according to permissions.
- Laravel Gates and model policies use the same permission names, for example `view events`, `create gallery`, `update news`, `delete users`.
- Login/logout and model CRUD changes are recorded in the Activity Log resource.

## Notes

- Demo admin users and roles are included through seeders.
- Uploads are stored on the `public` disk.
- Provided logo image is integrated as fallback at `public/images/alumni-logo.jpeg`.
