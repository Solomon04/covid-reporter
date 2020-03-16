<?php


namespace App\Entities;


class State
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $abbreviation;

    public function cast(object $class)
    {
        if(property_exists($class, 'name')){
            $this->name = $class->name;
        }

        if(property_exists($class, 'abbreviation')){
            $this->abbreviation = $class->abbreviation;
        }

        return $this;
    }
}
