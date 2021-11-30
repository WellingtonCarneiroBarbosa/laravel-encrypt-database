# Laravel Encrypt Database
Automatic Encrypt and Decrypt your database data. Tested and used on Laravel 8. I'm building yet automated tests

## Installing

Install the package
```
composer require wellingtoncarneirobarbosa/laravel-encrypt-database
```

Add the service provider on your providers list config/app.php:168
```
WellingtonCarneiroBarbosa\EncryptDatabase\Providers\EncryptDatabaseProvider::class,
```

## Usage

Replace your models. 
```
use WellingtonCarneiroBarbosa\EncryptDatabase\Models\EncryptableAuthenticatable;

class User extends EncryptableAuthenticatable {}
```

```
use WellingtonCarneiroBarbosa\EncryptDatabase\Models\EncryptableModel;

class Model extends EncryptableModel {}
```

List the encryptable fields
```
/**
* The attributes that should be encrypted.
*
* @var array
*/
protected $encryptable = [
    
];
```

Note if you have a mutator on your model like "setNameAttribute" you should implement manually the encrypt method

```
public function setNameAttribute(string $value) {
  $value = ucwords($value);
  
  $this->attributes['name'] = $this->encrypt('name', $value);
}
```

You also should implement manually decrypt method if you have an acessor method

```
public function getNameAttribute()
{
  $value = strtolower($this->attributes['name']);
  
  return $this->decrypt('name', $value);
}

```
