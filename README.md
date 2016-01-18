# Talk

Talk is a Laravel 5 based user conversation (inbox) system. You can easily integrate this package with your all laravel based project. Its help to you to develop a messeging system in 25 mins. So lets start :)

![Talk Screenshot](http://i.imgur.com/ELqGVrx.png?1 "Talk Conversation System")

### Installation

Talk is a laravel package so you can install it via composer. write this code in your terminal from your project

```
composer require nahid/talk
```

Wait for a while, composer will be autometically install it in your project.

### Configuration

After downloading complete now you have to call this package service in `config/app.php` config file. So add this line in `app.php` `providers` section

```php
Nahid\Talk\TalkServiceProvider::class,
```

Now run this command in your terminal for publish package resources

```
php artisan vendor:publish
```

After run this command all neccessary file will be included with your project. This package has two default migration and two models. So you have to run migrate command like these. Make sure your database configuration was complete.

```
php artisan migrate
```

### Usage

Talk conversation system work with laravel builtin authentication system so make sure you are using `Auth`. Talk work with your system users so your to insert your user table name in `config/talk.php` 

```php
'user_table'	=>	'your_user_table_name_without_prefix'
```