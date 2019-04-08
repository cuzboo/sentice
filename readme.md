## Sentice Test

Solution for the test sent via email. The application is made in laravel.To run it, please follow the steps below:

- Clone the repository.
- run command 'composer install'.
- run command 'php artisan key:generate'.
- Copy .env.exemple file to .env file.
- create database and change DB_DATABASE in .env to match newly created one, provide DB_USERNAME and DB_PASSWORD for the database access.
- run the comand 'php artisan migrate' to create tables.

## Tasks:

- run the command 'php artisan db:seed' to add 30 new users (with random bonus rate) and unique email.
- run the command 'php artisan serve' and the site will be lounched localy on port 8000.
- on route '/edit-random-user' one random user will be changed.
- to add money on user's account run the url '/add/user_id/amount'. Ex. '/add/1/200 '.
- to withdraw money from user's account run the url /withdraw/user_id/amount . Ex. '/withdraw/1/200 '.
- try to withdraw more money than user has on his account. Ex. '/withdraw/1/800 '.
- try to add 'non numeric' amount. Ex. '/add/1/verbal '.
- to see report for 7 previous days run '/report' or put the date in format 'YYYY-MM-DD' after it for the report from specific date (Ex. '/report/2018-05-12')

## Code

- Every route runs from app/Http/Controllers/CustomerController.php .
- Test it running 'vendor/bin/phpunit' from comand line.