<?php


namespace App\Services;


use App\Contracts\LocationValidation;
use App\Entities\Country;
use App\Entities\State;
use App\Exceptions\LocationNotFoundException;
use Illuminate\Filesystem\Filesystem;

class LocationValidationProvider implements LocationValidation
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Validate a country.
     *
     * @param string $country
     * @return Country
     * @throws LocationNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function countryValidation(string $country)
    {
        $file = $this->filesystem->get(storage_path('countries.json'));
        $countries = json_decode($file);
        $countries = array_filter($countries, function ($item) use ($country){
            if($country == $item->name || $country == $item->code){
                return $item;
            }
        });
        $countries = collect($countries);
        if ($countries->isEmpty()){
            throw new LocationNotFoundException(sprintf('The country "%s" was not found', $country));
        }

        return (new Country())->cast($countries->first());
    }

    /**
     * Validate a US state
     *
     * @param string $state
     * @return State
     * @throws LocationNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function stateValidation(string $state)
    {
        $file = $this->filesystem->get(storage_path('states.json'));
        $states = json_decode($file);
        $states = array_filter($states, function ($item) use ($state){
            if($state == $item->name || $state == $item->abbreviation){
                return $item;
            }
        });
        $states = collect($states);
        if($states->isEmpty()){
            throw new LocationNotFoundException(sprintf('The state "%s" was not found', $state));
        }


        return (new State())->cast($states->first());
    }
}
