<?php

namespace App\Commands;

use App\Contracts\LocationValidation;
use App\Exceptions\LocationNotFoundException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\PendingRequest;
use LaravelZero\Framework\Commands\Command;

class ReportCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'report';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'This generates a report of confirmed covid cases in a location.';

    /**
     * @var PendingRequest
     */
    private $pendingRequest;

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $locationValidation;

    public function __construct(PendingRequest $pendingRequest, Filesystem $filesystem, LocationValidation $locationValidation)
    {
        parent::__construct();
        $this->pendingRequest = $pendingRequest;
        $this->filesystem = $filesystem;
        $this->locationValidation = $locationValidation;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Validate country
        $answer = $this->ask('What is your country code?', 'US');
        try{
            $country = $this->locationValidation->countryValidation($answer);
        }catch (LocationNotFoundException $exception){
            $this->error($exception->getMessage());
            return;
        }

        // Validate url
        if(is_null(config('http.url'))){
            $this->error('Please set the url in the config.');
            return;
        }
        $url = str_replace('{country}', $country->code, config('http.url'));

        // Validate US State
        $state = null;
        if($country->code == 'US'){
           $answer = $this->ask('What is your state?', 'MN');
           try{
               $state = $this->locationValidation->stateValidation($answer);
           }
           catch (LocationNotFoundException $exception){
               $this->error($exception->getMessage());
               return;
           }
        }

        // Make API Request
        $response = $this->pendingRequest->get($url)->json();
        if(!is_null($state)){
            $response = array_filter($response, function ($item) use ($state){
                if ($item['provinceState'] == $state->name){
                    return $item;
                }
            });
        }

        $report = collect($response);
        $report = $report->first();
        if(!is_null($state)){
            $message = sprintf('There are %d active cases in %s, %s. %d of them have resulted in deaths.',
                $report['active'],
                $state->abbreviation,
                $country->code,
                $report['deaths']
            );
        }else {
            $message = sprintf('There are %d active cases in %s. %d of them have resulted in deaths.',
                $report['active'],
                $country->code,
                $report['deaths']
            );
        }

        $this->alert($message);
        $this->notify('Covid Recent Report', $message, storage_path('news.png'));
        return;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
         $schedule->command(static::class)->hourly();
    }
}
