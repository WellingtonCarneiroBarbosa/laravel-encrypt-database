<?php

namespace WellingtonCarneiroBarbosa\EncryptDatabase\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use WellingtonCarneiroBarbosa\EncryptDatabase\Encrypter;
use WellingtonCarneiroBarbosa\EncryptDatabase\Traits\HasEncryptedAttributes;

class EncryptableModel extends EloquentModel
{
    use HasEncryptedAttributes;

    /**
     * If attribute mutate should be skipped
     *
     * @var boolean
     */
    public $preventAttrMutator = false;

     /**
     * If attribute getter should be skipped
     *
     * @var boolean
     */
    public $preventAttrGetter = false;

    /**
     * If should enable encryptation
     *
     * @var boolean
     */
    public $enableEncryption = true;

    /**
     * The attributes that should be encrypteds.
     *
     * @var array
     */
    protected $encryptable = [];

    /**
     * @return array
     */
    public function getEncryptableAttributes()
    {
        return $this->encryptable;
    }

    public function isEncryptable($attribute_key) {
        if($this->enableEncryption){
            return in_array($attribute_key, $this->encryptable);
        }

        return false;
    }

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

    public function encrypt($attribute_key, $value)
    {
        if($this->isEncryptable($attribute_key)
            && $value
            && (!is_null($value))
            && $value != ''
        ) {
            return Encrypter::encrypt($value);
        }

        return $value;
    }
}
