<?php

namespace WellingtonCarneiroBarbosa\EncryptDatabase\Traits;

use WellingtonCarneiroBarbosa\EncryptDatabase\Encrypter;
use WellingtonCarneiroBarbosa\EncryptDatabase\Builders\EncryptionEloquentBuilder;

trait EncryptableModel
{
    // Extend EncryptionEloquentBuilder
    public function newEloquentBuilder($query)
    {
        return new EncryptionEloquentBuilder($query);
    }

    /**
     * @return array
     */
    public function getEncryptableAttributes()
    {
        return $this->encryptable;
    }

    /**
     * Checks if the attribute is encryptable
     *
     * @param string $attribute_key
     * @return boolean
     */
    public function isEncryptable($attribute_key) {
        if(config('database_encryption.enable_encryption')) {
            return in_array($attribute_key, $this->encryptable);
        }

        return false;
    }

    /**
     * Decrypt a given value
     *
     * @param string $attribute_key
     * @param mixed $value
     * @return mixed
     */
    public function decrypt($attribute_key, $value)
    {
        if($this->isEncryptable($attribute_key)) {
            if($value && (!is_null($value)) && $value != '') {
                $decrypted = Encrypter::decrypt($value);

                if(! $decrypted) {
                    return $value;
                }

                return $decrypted;
            }
        }

        return $value;
    }

    /**
     * Encrypt a given value
     *
     * @param string $attribute_key
     * @param mixed $value
     * @return mixed
     */
    public function encrypt($attribute_key, $value)
    {
        if($this->isEncryptable($attribute_key)) {
            if($value && (!is_null($value)) && $value != '') {
                return Encrypter::encrypt($value);
            }
        }

        return $value;
    }

    // -----------------------------
    // Attributes modifiers
    // -----------------------------
    public function setAttribute($key, $value)
    {
        if (! $this->hasSetMutator($key)) {
            $value = $this->encrypt($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return parent::getAttribute($key);
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key) {
        return $this->transformModelValue($key, $this->getAttributeFromArray($key));
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        return $this->decrypt($key, $this->getAttributes()[$key] ?? null);
    }

    /**
     * Encrypt an array
     *
     * @param array $items
     * @return array
     */
    protected function encryptArray(array $items)
    {
        $encrypted_items = [

        ];

        foreach($items as $item)
        {
            array_push($encrypted_items, Encrypter::encrypt($item));
        }

        return $encrypted_items;
    }

    /**
     * Encrypt an array
     *
     * @param array $items
     * @return array
     */
    protected static function staticEncryptArray(array $items)
    {
        $encrypted_items = [

        ];

        foreach($items as $item)
        {
            array_push($encrypted_items, Encrypter::encrypt($item));
        }

        return $encrypted_items;
    }
}
