# Research Methods Syllabus

## Development
This platform is built using Laravel/PHP and uses Filament.

### Setup Local Environment
1.	Clone repo: `git clone git@github.com:stats4sd/rms-syllabus.git`
2.	Copy `.env.example` as a new file and call it `.env`
3.	Update variables in `.env` file to match your local environment:
    1.	Check APP_URL is correct
    2.	Update DB_DATABASE (name of the local MySQL database to use), DB_USERNAME (local MySQL username) and DB_PASSWORD (local MySQL password)
4.	Create a local MySQL database with the same name used in the `.env` file
5.	Run the following setup commands in the root project folder:
```
composer install
php artisan key:generate
npm install
npm run dev
```
6.	Migrate the database: `php aritsan migrate:fresh --seed` (or copy from the staging site)



