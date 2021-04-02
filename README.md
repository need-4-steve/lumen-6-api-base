# Lumen 6 (8.x is latest, but this is the LTS version) API Project Starter Setup
This is a boilerplate/start Lumen project setup using the Long Term Support version of Laravel's Lumen micro-framework [laravel/lumen^6.3.4](https://github.com/laravel/lumen). This setup is optimized for quickly and easily getting a new REST API started with only minimal configuration required. This is an amalgamation of personal experience and insight from other developers through blog articles, documentation, tutorials, laracasts, and OSI projects. Suggestions and pull requests are totally encouraged!

Thanks for checking it out!
*Need-4-Steve*

## Base Features
* REST API-Ready
* * using the awesome [DingoAPI (dingo/api) package](https://github.com/dingo/api)
* JWT Authentication
* * using the ubiquitous[tymon/jwt-auth package](https://github.com/tymondesigns/jwt-auth)
* CORS-Enabled makes local development way easier!
* * uses [palanik/lumen-cors package](https://github.com/palanik/lumen-cors)
* Artisan Generators
* * using the [flipboxstudio/lumen-generator package](https://github.com/flipboxstudio/lumen-generator) since Lumen doesn't contain Artisan generators
* Documentation Generator for all Endpoints
* * uses the [cbxtechcorp/lumen-api-routes-list package](https://github.com/marco-gallegos/lumen-api-route-list)
* Meant to be used with PHP 7.4+, works with PHP 8.0+ as well!
![PHP 7.4+](https://img.shields.io/badge/PHP-7.4-blue?style=for-the-badge)
* Free to use, modify, collaborate, extend, love, hate, totally ignore, or do whatever you want with
![MIT License](http://opensource.org/licenses/MIT)
* Uses the Long Term Support version of Lumen (6.x LTS)
![LUMEN 6.X LTS](https://img.shields.io/badge/LUMEN-6.X-orange?style=for-the-badge)

## Useful Plugins

if you'r using composer v1.x check [this project](https://github.com/hirak/prestissimo)

```bash
composer global require hirak/prestissimo
```

## TODO

- [ ] Still need to add some unit testing
- [ ] Add a Vue.js Frontend Generator Option

## To use the documentation generator for your API

```bash
apidoc -i App/Http/Controller/Api/v1 -o public/apidoc
```

## To List All of the API Routes

```bash
php artisan api:route
```

## Use Artisan Generators

By default lumen doesn't support the useful laravel/artisan generators, but with [this extension](https://github.com/flipboxstudio/lumen-generator), we can use the following commands to create whatever you need:

```
key:generate         Set the application key

make:cast            Create a new custom Eloquent cast class
make:channel         Create a new channel class
make:command         Create a new Artisan command
make:controller      Create a new controller class
make:event           Create a new event class
make:exception       Create a new custom exception class
make:factory         Create a new model factory
make:job             Create a new job class
make:listener        Create a new event listener class
make:mail            Create a new email class
make:middleware      Create a new middleware class
make:migration       Create a new migration file
make:model           Create a new Eloquent model class
make:notification    Create a new notification class
make:pipe            Create a new pipe class
make:policy          Create a new policy class
make:provider        Create a new service provider class
make:request         Create a new form request class
make:resource        Create a new resource
make:seeder          Create a new seeder class
make:test            Create a new test class

notifications:table  Create a migration for the notifications table

schema:dump          Dump the given database schema
```

### Additional Useful Commands

```
clear-compiled    Remove the compiled class file
serve             Serve the application on the PHP development server
tinker            Interact with your application
optimize          Optimize the framework for better performance
route:list        Display all registered routes.
```

### Test:

```bash
php artisan make:model MyModel
```

## LINKS

- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- json-web-token(jwt) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc [apidocjs](http://apidocjs.com/)
- Rest API guidance [jsonapi.org](http://jsonapi.org/format/)
- Rest API Debug [Insomnia](https://insomnia.rest/)
- A good article [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)

## Installation

### 1. Install the starter repo

#### Using GIT

``` bash
git clone https://github.com/need-4-steve/lumen-6-api-base.git api
cd api
composer install
cp .env.example .env
php artisan jwt:secret
```

#### Using HTTPS
``` bash
gh repo clone GeekyAnts/laravel-lumen-jwt-boilerplate api
cd api
composer install
cp .env.example .env
php artisan jwt:secret
```
#### Alternatively:
1. Using your browser of choice, go to https://github.com/need-4-steve/lumen-6-api-base/
2. Click the green "Code" button
3. Download ZIP or Open with GitHub Desktop
4. Extract *.zip file to preferred installation directory, if downloaded as ZIP
5. Open command line to installation directory
6. ```bash composer install ```
7. Copy the file .env.example and paste in the same directory, then rename to .env
8. ```bash php artisan jwt:secret```
9. PROFIT!

### 2. Configuration

Edit the .env file and configure to match your dev environment.

```bash
nano .env
# edit all DB_* fields
# configure values to match the appropriate values for database access
  
# edit APP_KEY field
# key:generate is abandoned in lumen, but you can generate a key yourself
# using any of the following commands:
php -r "echo md5(uniqid());"
php -r "echo str_random(32);" 
# or whatever you want to generate a 32 character (128-bit) string，
# you could also jsut use jwt:secret, and cut and paste it into the APP_KEY field,
# then jwt:secret again to generate a new JWT secret
php artisan migrate --seed
```

### 3. Up and Running

#### PHP's built-in server

```bash
php -S localhost:8000 -t public
# or
php artisan serve
```

#### Docker Container

The easiest way to use docker for this lumen version is to install laravel/sail.

```bash
composer require laravel/sail --dev
# the command below will publish the docker-compose.yml file in your project root
php artisan sail:install
# make any changes you want to make, then fire it up!
./vendor/bin/sail up
```

Refer to the [Laravel Sail documentation](https://laravel.com/docs/8.x/sail) for more information.

#### Deploy to Production Environment

In a production environment, omit the composer dev packages:

```bash
composer install --no-dev
```

## Routes

There is jsut 1 built-in route to this starter framework.

### Authorization Endpoint

The login route returns an access token to make any other request.

route uri : http://{base_uri}/api/v1/auth
http verb : post
params    : {
  email: "youremail@domain.com",
  password: "userpassword"
}

## FAQs

<details>
  <summary>About JWT</summary>

- There is no session and auth guard in lumen 6, so modify `config/auth.php`. 
- The User Model class must implement `Tymon\JWTAuth\Contracts\JWTSubject`
</details>

<details>
  <summary>How to use mail</summary>

- composer require `illuminate/mail` and `guzzlehttp/guzzle`
- register email service in `bootstrap/app.php` or register whatever `provider`
- add `mail.php` & `services.php` in config
- - **HINT:** just copy them from laravel
- add `MAIL_DRIVER` key with appropriate value in .env file
</details>

<details>
  <summary>How to use the transformer classes</summary>

  Transformers are application layers that help you format your resource responses and their relationships. Basically, they wrap your responses as JSON most of the time
  but other logic can go there to based on your needs. Check out this [blog post](http://niceprogrammer.com/lumen-api-tutorial-response-transformers-with-php-leagues-fractal/) for more info!

</details>

<details>
  <summary>transformer data serializer</summary>

  dingo/api uses [Fractal](http://fractal.thephpleague.com/) for transformer resources. Learn more by checking out Fractals Serializer documentation
  [http://fractal.thephpleague.com/serializers/](http://fractal.thephpleague.com/serializers/)。
  
  You can set your own serializer like this (***NOTE: `DataArray` is the default assigned type)***：

  `bootstrap/app.php`
  ```php
  $app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
    $fractal = new League\Fractal\Manager;
    // $serializer = new League\Fractal\Serializer\JsonApiSerializer();
    $serializer = new League\Fractal\Serializer\ArraySerializer();
    // $serializer = new App\Serializers\NoDataArraySerializer();
    $fractal->setSerializer($serializer);,
    return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
  });
```
</details>