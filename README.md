# Laravel Encrypt Database
Automatic Encrypt and Decrypt your database data. Tested and used on Laravel 8. I'm yet building the tests.

## Features
- Minimal configuration
- Encrypt and Decrypt database fields easily
- Include searching encrypted data using the following: whereEncrypted and orWhereEncrypted
- Include unique_encrypted, exists_encrypted rules 
- Uses openssl for encrypting and decrypting fields

## Requirements
Laravel >= 8.0
<br>
PHP >= 7.4

## Installing

Install the package
```
composer require wellingtoncarneirobarbosa/laravel-encrypt-database
```

Add the service provider on your providers list config/app.php:168
```
WellingtonCarneiroBarbosa\EncryptDatabase\Providers\EncryptDatabaseProvider::class,
```

Publish the config file
```
php artisan vendor:publish --tag=laravel-database-encryption
```

## Usage

It is highly recommended to alter your column types to ```TEXT``` or ```LONGTEXT```


Just use the trait on your encryptable models and list the encryptable fields.
```
use WellingtonCarneiroBarbosa\EncryptDatabase\Traits\EncryptableModel;

class User extends Authenticable
{
    Use HasFactory,
        EncryptableModel;
        
    /**
    * The attributes that should be encrypted.
    *
    * @var array
    */
    protected $encryptable = [
        'name',
        'email',
        'birth_date',
    ];
}
```

<br>
Note if you have a mutator on your model like "setNameAttribute" you should implement manually the encrypt method

```
public function setNameAttribute(string $value)
{
  $value = ucwords($value);
  
  $this->attributes['name'] = $this->encrypt('name', $value);
}
```

<br>
You also should implement manually decrypt method if you have an acessor method

```
public function getNameAttribute()
{
  $value = strtolower($this->attributes['name']);
  
  return $this->decrypt('name', $value);
}

```

If you are validating your form data with 'unique' or 'exists' you should replace it to ```unique_encrypted``` and ```exists_encrypted``` respectively
