# docker-laravel
An example of a dockerized Laravel application

## Quick setup for local development
Below setup has been tested on Ubuntu. On Linux, the default Docker installation needs to be run as root.
1. Ensure [docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/) are installed locally
2. Install Laravel dependencies using composer

    `docker run --rm --volume $PWD:/app composer:latest composer install`

3. Create a symbolic link to the version controlled local env file

    `ln -s .env.local .env`

4. Run the application in the foreground or background

    `docker-compose up`

    or

    `docker-compose up -d`

5. Install the migrations table

    `docker-compose exec backend php artisan migrate:install`

6. Run the database migrations to create application tables

    `docker-compose exec backend php artisan migrate`

7. (Linux) Ensure `storage` folder is writable by the php-fpm process

    `chmod -R 777 storage`

8. Open `localhost:8080` in a web browser to check that the application is serving properly

8. (Optional) Run tests. Note that this will clear current data in the database.

    `docker-compose exec backend php artisan test`

7. Seed test user

    `docker-compose exec backend php artisan db:seed`

8. Start playing around with the API! The default base URL for local environment is `localhost:8080`

## API documentation

Postman documentation: https://documenter.getpostman.com/view/1087706/TzskENvB

