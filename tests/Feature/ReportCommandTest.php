<?php


namespace Tests\Feature;


use Tests\TestCase;

/**
 * @covers \App\Commands\ReportCommand
 */
class ReportCommandTest extends TestCase
{
    public function testReportCommandSuccess()
    {
        $this->artisan('report')
            ->expectsQuestion('What is your country code?', 'US')
            ->expectsQuestion('What is your state?', 'MN')
            ->assertExitCode(0);
    }

    public function testReportCommandCountryError()
    {
        $this->artisan('report')
            ->expectsQuestion('What is your country code?', 'Foo')
            ->expectsOutput('The country "Foo" was not found')
            ->assertExitCode(0);
    }

    public function testReportCommandStateError()
    {
        $this->artisan('report')
            ->expectsQuestion('What is your country code?', 'US')
            ->expectsQuestion('What is your state?', 'Bar')
            ->expectsOutput('The state "Bar" was not found')
            ->assertExitCode(0);
    }
}
