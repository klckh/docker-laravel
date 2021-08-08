# docker-laravel
An example of a dockerized Laravel application

## Quick setup for local development
Below setup has been tested on Ubuntu. On Linux, the default Docker installation needs to be run as root.
1. Ensure [docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/) are installed locally
2. Install Laravel dependencies using composer

    `docker run --rm --volume $PWD:/app composer:latest composer install`

3. Create a symbolic link to the version controlled local env file

    `ln -s .env.local .env`

4. Run the application in the foreground

    `docker-compose up`

5. Install the migrations table

    `docker-compose exec backend php artisan migrate:install`

6. Run the database migrations to create application tables

    `docker-compose exec backend php artisan migrate:status`

7. Seed test user

    `docker-compose exec backend php artisan db:seed`

7. (Linux) Ensure `storage` folder is writable by the php-fpm process

    `chmod -R 777 storage`
