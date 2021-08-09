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

## Design decisions

* Due to limited time, features were kept simple to allow a minimum viable project to be implemented quickly
* Caddy is used as the web server due to ease of setup
* MySQL is used as the database because I'm most familiar with it
* Authentication is implemented using Laravel Sanctum which provides simple session based authentication for single page applications. This is faster and more reliable than implementing from scratch, especially given the time constraints
* All APIs return a response built using `buildResponse()` to maintain consistency in the return format. It also allows the format be be extended and refactored easily in the future
* Currently the update API doesn't consider multiple users concurrently updating the same item. This can be solved by requiring the frontend to pass a `last_updated_at` timestamp when updating an item, and comparing it to the current `updated_at` timestamp. If the timestamp is different, it means the item was changed during the time the user was viewing the item, and the backend should return a 409 Conflict status to tell the frontend to retrieve the latest version of the item. If the timestamp is the same, the update can proceed. For this to be completely reliable, the timestamp check and update needs to be done in one atomic operation (database record locks can be used to achieve this)
* Due to limited time, I've only implemented a minimal set of tests for the items API to demonstrate testing code. It would take too much time to fully cover every possible edge case.
* The `docker-compose.yml` defines a set of containers which are useful for local development, but shouldn't really be used in production

## Frontend

* The frontend would ideally be built as a single page application using a framework such as Vue.js or React
* A suitable router should be used to map URL to different page views
* The application should provide the following pages at a minimum:
1. Login
2. List of items
* Currently there are only 3 fields for items, so a separate details page shouldn't be needed. Create, edit, delete, and update can all be done inline on the items listing page for better UX.
* The UI should display the result of every request to the user using something non-intrusive like a floating toast. For non 200 HTTP status code repsonses, the error message can be displayed and styled to draw attention to it. All responses can follow the same logic of displaying the `message` field provided by the server, regardless of whether it was a successful or failed response
* For HTTP 401 (unauthenticated) or 419 (CSRF token expired), the UI should direct the user to the login page to start the login process again. The frontend should be ready to do this on any request.

## Further work
Around 4 hours were spent on this project. If there was more time I would like to do the following:
* Support concurrent item update requests as described previously
* Learn how to use CloudFormation and add a template for this project
* Improve test coverage
* Add a production ready docker-compose.yml configuration
