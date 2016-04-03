# Disclaimer

This is a __drastically__ modified version of the official Mogreet PHP repo, which you can find [here](https://github.com/jperichon/mogreet-php).
Please use that one if you are running PHP < 5.5 or if you, for some reason, do not use `namespace`s in your app.

***
## Introduction

This is a PHP wrapper for the Mogreet API.

## Installation

The installation is done via Composer:

```php
     composer require impeto/mogreet-php
```

Or by cloning this repo:
    
    git clone https://github.com/impeto/mogreet-php.git

## Usage examples

### Create a client

There are two ways to create a client. One is by providing the Mogreet `clientId` and `token` to the `Mogreet\Client` class' constructor, like this:
```php
<?php
...
use Mogreet\Client;
...

     $clientId = 'xxxxx'; // Your Client ID from https://developer.mogreet.com/dashboard
     $token = 'xxxxx'; // Your token from https://developer.mogreet.com/dashboard
     $client = new Client( $clientId, $token);
```
The other way is by storing the `clientId` and `token` in two environment variables named `MOGREET_CLIENT_ID` and `MOGREET_TOKEN` respectively. Instantiating the `Mogreet` class with an empty constructor will instantiate the class with these values:

```php
     $client = new Mogreet\Client();
```

## Laravel Support

The package includes a Laravel `ServiceProvider` as well as a `Facade` for easy access to functionality in Laravel style. All you have to do is to register the Service Provider and the Facade with the Laravel App in `config/app.php` file:

```php
     ...
     'providers' => [
          ...
          Mogreet\Laravel\MogreetServiceProvider::class,
          ...
     ],
     ...
     'aliases' => [
          ...
          'Mogreet' => Mogreet\Laravel\MogreetFacade::class,
          ...
     ]
```
 Then you can use it like this:
 
```php
     ...
     $result = Mogreet::system()->ping();
     //or
     $result = app('mogreet')->system()->ping();
     //or
     $result = app(Mogreet\Client::class)->system()->ping();
     return $result->status;
```

### Configuration

When resolving the `Mogreet\Client` object form the Laravel IoC, using the `Mogreet` facade, `app('mogreet')` or `app(Mogreet\Client::class)`, the `MogreetServiceProvider` will check for the existence of a `config/mogreet.php` file. If the file exists, it will use Laravel's `Config` mechanism to obtain the `client_id` and `token` values, like this: `Config::get( 'mogreet.client_id')` and `Config::get( 'mogreet.token')`. So, what you have to do is create a `config/mogreet.php` file with the following contents:

```php
<?php
return [
     'client_id' => 'your client id here',
     'token' => 'your token here'
];
```
If the config file does not exist, then `MogreetServiceProvider` will instantiate the `Client` class with an empty constructor, which means that the `client_id` and `token` values will be obtained from the environment variables, as explained above. In order to use this package in Laravel style, you must at the least create the environment variables `MOGREET_CLIENT_ID` and `MOGREET_TOKEN`; otherwise you will have to instantiate the `Client` class manually as if you were using vanilla PHP.

### Ping

```php

$response = $client->system()->ping();
echo $response->message;
```

### Send an SMS to one recipient

```php

$response = $client->transaction()->send(array(
    'campaign_id' => 'xxxxx', // Your SMS campaign ID from https://developer.mogreet.com/dashboard
    'to' => '9999999999',
    'message' => 'Hello form Mogreet API!'
));
echo $response->messageId;
```

### Send an MMS to one recipient

```php

$response = $client->transaction()->send(array(
    'campaign_id' => 'xxxxx', // Your MMS campaign ID from https://developer.mogreet.com/dashboard
    'to' => '9999999999',
    'message' => 'This is super easy!',
    'content_url' => 'https://wp-uploads.mogreet.com/wp-uploads/2013/02/API-Beer-sticker-300dpi-1024x1024.jpg'
));
print $response->messageId;
```
### Upload a media file

```php

$response = $client->media()->upload(array(
    'type' => 'image',
    'name' => 'mogreet logo',
    'file' => '/path/to/image/mogreet.png',
    // to ingest a file already online, use: 'url' => 'https://wp-uploads.mogreet.com/wp-uploads/2013/02/API-Beer-sticker-300dpi-1024x1024.jpg'
));
echo $response->media->smartUrl;
echo '<br/>';
echo $response->media->contentId;
```

### List all medias

```php

$response = $client->media()->listAll();
foreach($response->mediaList as $media) {
    echo $media->contentId . ' => ' . $media->name . ' ' . $media->smartUrl . '<br />';
}
```

## Notes

With the Response object, you can print the plain JSON response of the API
call (`echo $response`), or access directly a field (e.g: $response->message).

Due to the keyword restriction on 'list' and the existing function 'empty()' in
PHP, I changed the mappings of the following API calls:

- $client->*->listAll() maps to the method list
- $client->list->pruneAll() maps to 'list.empty'


## [Full Documentation](https://developer.mogreet.com/docs)

The full documentation for the Mogreet API is available [here](https://developer.mogreet.com/docs)

## Prerequisites

* PHP >= 5.5
* The PHP JSON extension
