<?php


namespace Tests\Unit;


use App\Contracts\LocationValidation;
use App\Entities\Country;
use App\Entities\State;
use App\Exceptions\LocationNotFoundException;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\LocationValidationProvider
 */
class LocationValidationProviderTest extends TestCase
{
    /**
     * @var LocationValidation|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    protected $locationValidation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->locationValidation = \Mockery::mock(LocationValidation::class);
    }

    /**
     * @covers ::stateValidation
     */
    public function testStateValidationSuccess(): void
    {
        $state = new State();
        $state->name = 'Minnesota';
        $state->abbreviation = 'MN';

        $this->locationValidation->shouldReceive('stateValidation')
            ->with('MN')
            ->andReturn($state);

        $response = $this->locationValidation->stateValidation('MN');
        $this->assertSame($state, $response);
    }

    /**
     * @covers ::stateValidation
     */
    public function testStateValidationError(): void
    {
        $this->expectException(LocationNotFoundException::class);
        $this->locationValidation->shouldReceive('stateValidation')
            ->with('Foo')
            ->andThrow(new LocationNotFoundException());

        $this->locationValidation->stateValidation('Foo');
    }

    /**
     * @covers ::countryValidation
     */
    public function testCountryValidationSuccess(): void
    {
        $country = new Country();
        $country->name = 'United States';
        $country->code = "US";

        $this->locationValidation->shouldReceive('countryValidation')
            ->with('US')
            ->andReturn($country);

        $response = $this->locationValidation->countryValidation('US');
        $this->assertSame($country, $response);
    }

    /**
     * @covers ::countryValidation
     */
    public function testCountryValidationError(): void
    {
        $this->expectException(LocationNotFoundException::class);
        $this->locationValidation->shouldReceive('countryValidation')
            ->with('Foo')
            ->andThrow(new LocationNotFoundException());

        $this->locationValidation->countryValidation('Foo');
    }
}
