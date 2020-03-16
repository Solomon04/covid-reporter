<?php


namespace App\Entities;


class Country
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $code;

    public function cast(object $class)
    {
        if(property_exists($class, 'name')){
            $this->name = $class->name;
        }

        if(property_exists($class, 'code')){
            $this->code = $class->code;
        }

        return $this;
    }
}
