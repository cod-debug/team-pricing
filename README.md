## Setup laravel application
After cloning the app, move to project root directory and follow these steps to run the app locally.

### Installing php dependencies
```bash  
composer install
```
or

```bash  
composer install --ignore-platform-reqs
```

### Installing node dependencies
```bash  
npm install
```

### Migrate database
```bash  
php artisan migrate
```

### Run seeders
load default system values
```bash  
php artisan db:seed
```

### Run laravel server
```bash  
php artisan serve
```

### Open another terminal and run
```bash  
npm run dev
```

### Default users note: passwords are the same 'password'
- System Admin
  - email: system.admin@email.com
- Team Admins
  - email: teama.admin@email.com
  - email: teamb.admin@email.com
- Team Members
  - email: teama.member@email.com
  - email: teamb.member@email.com