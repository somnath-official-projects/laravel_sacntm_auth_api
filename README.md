# Laravel Sanctum | Api Authentication 

## Official documentation link
[Laravel Sanctum](https://laravel.com/docs/8.x/sanctum#token-abilities)

## Project requirements
- Laravel version: 8
- php version: 8

## Installation guide
- Clone this repo and run `composer install`
- Copy `.env.example` and rename it to `.env`
- Add the Database details in `.env` file
- In terminal/cmd run `php artisan key:generate` and `php artisan migrate`

## Running this project
- In terminal/cmd run `php artisan serve`

## Api description
|Path|Method|Headers|Body|
|------|----------|-----------|-------|
|/api/register|POST|{'Accept': 'application/json'}|{"name","email", "password","password_confirmation"}|
|/api/login|POST|{'Accept': 'application/json'}|{"email", "password"}|
|/api/logout|POST|{Accept: 'application/json', 'Authorization': 'Bearer {{token}}'}|none|
|/api/logout-all|POST|{Accept: 'application/json', 'Authorization': 'Bearer {{token}}'}|none|