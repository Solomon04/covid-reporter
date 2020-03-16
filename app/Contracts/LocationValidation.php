<?php


namespace App\Contracts;


use App\Entities\Country;
use App\Entities\State;
use App\Exceptions\LocationNotFoundException;

interface LocationValidation
{
    /**
     * Validate a country.
     *
     * @param string $country
     * @return Country
     * @throws LocationNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function countryValidation(string $country);

    /**
     * Validate a US state
     *
     * @param string $state
     * @return State
     * @throws LocationNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function stateValidation(string $state);
}
