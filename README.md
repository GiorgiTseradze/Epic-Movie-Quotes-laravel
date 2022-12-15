
<div style="display:flex; align-items: center">
  <h1 style="position:relative; top: -6px" >Epic Movie Quotes</h1>
</div>

---
This app helps you find and post your favorite movies and movie quotes. You have wide profile functionality and ability to add edit or delete movie quotes. App is responsive. It has infinite scroll, realtime notifications, comments and search, translations and it is pretty pretty.

Page link: https://epic-movie-quotes.giorgi-tseradze.redberryinternship.ge/

#
### Table of Contents
* [Prerequisites](#prerequisites)
* [Tech Stack](#tech-stack)
* [Getting Started](#getting-started)
* [Migrations](#migration)
* [Development](#development)
* [SQL tables](#SQL-tables)

#
### Prerequisites

* *PHP 8.1.9*
* *MYSQL@8.0.30*
* *npm@6.14.17*
* *composer@2.4.1*


#
### Tech Stack

* [Laravel@9.19](https://laravel.com/docs/9.x) - back-end framework
* [Spatie Translatable](https://github.com/spatie/laravel-translatable) - package for translation
* Axios 1.1.2
* Swagger 4.15.5

#
### Getting Started
1\. First of all you need to clone repository from github:
```sh
git clone 
```

2\. Next step requires you to run *composer install* in order to install all the dependencies.
```sh
composer install
```

3\. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:
```sh
npm install
```

and also:
```sh
npm run dev
```
in order to build your JS/SaaS resources.

4\. Now we need to set our env file. Go to the root of your project and execute this command.
```sh
cp .env.example .env
```
And now you should provide **.env** file all the necessary environment variables:

#
**MYSQL:**
>DB_CONNECTION=mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE=*****

>DB_USERNAME=*****

>DB_PASSWORD=*****


##### Now, you should be good to go!


#
### Migration
if you've completed getting started section, then migrating database if fairly simple process, just execute:
```sh
php artisan migrate
```

#
### Running Unit tests
Running unit tests also is very simple process, just type in following command:

```sh
composer test
```

#
### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```

when working on JS you may run:

```sh
  npm run dev
```
it builds your js files into executable scripts.
If you want to watch files during development, execute instead:

```sh
  npm run watch
```
it will watch JS files and on change it'll rebuild them, so you don't have to manually build them.



#
### SQL Tables
![SQL tables](public/assets/drawsql-epic.png) 