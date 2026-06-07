# Commands to Run the Website

Open PowerShell and go to this project directory:

```powershell
cd "C:\Users\Admin\OneDrive\Documents\Alumni\alumni-association"
```

If this is the first time running the project, install dependencies:

```powershell
composer install
npm install
```

Prepare the database and storage link:

```powershell
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Start the Laravel website:

```powershell
php artisan serve
```

In another PowerShell window, start Vite for frontend assets:

```powershell
cd "C:\Users\Admin\OneDrive\Documents\Alumni\alumni-association"
npm run dev
```

Open the website in your browser:

```text
http://127.0.0.1:8000
```

Open the admin panel:

```text
http://127.0.0.1:8000/admin
```

Demo admin login:

```text
Email: superadmin@example.com
Password: Password@123
```

If port 8000 is already busy, run Laravel on another port:

```powershell
php artisan serve --host=127.0.0.1 --port=8010
```

Then open:

```text
http://127.0.0.1:8010
http://127.0.0.1:8010/admin
```
