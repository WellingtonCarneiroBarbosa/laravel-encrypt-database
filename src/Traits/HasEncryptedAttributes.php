<?php

namespace WellingtonCarneiroBarbosa\EncryptDatabase\Traits;

trait HasEncryptedAttributes
{
    public function setAttribute($key, $value)
    {
        if ($this->isEncryptable($key) && ! $this->hasSetMutator($key)) {
            $value = $this->encrypt($value);
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
}
