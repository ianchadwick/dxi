## DXI/EasyContactNow PHP LIBRARY

Outbound/Inbound Call Management. Automated Dialler & Contact Queuing

This package allows you to add contacts using their API.[See website](https://api.dxi.eu/docs/database-api.html)

## Requirements 

PHP 5.4+

## Installation using Composer

Add ianchadwick/dxi to the require part of your composer.json file

```
"require": {
  "ianchadwick/dxi": "1.0.*"
}
```

Then update your project with composer

```
composer update
```

## Basic Usage

```
use Dxi\Dxi;
use Dxi\Commands\Dataset\Contact\Create;

class MyClass {
  public function createContact()
  {
    // init the Dxi helper
    $dxi = new Dxi('myusername', 'mypassword');
    
    // create the command with the Dxi object
    $command = new Create($dxi);
    
    // set the params
    $command->setParams([
        'dataset' => 10,
        'firstname' => 'Ian',
        'lastname' => 'Chadwick',
        'ddi_mobile' => '07800000000'
    ]);
    
    // create the contact
    $response = $dxi->fire($command);
  }
}
```