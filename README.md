# pp1806
Laravel tutorial

- Create .env file from .env.example
- Config connect database
- Create APP_KEY
- Run command below:
```
composer install
yarn install
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=assets --force
```