<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EstablishmentOpeningHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'establishment_opening_hour:check_open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check and open or close a establishment automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Running Command Esablishment Automatically Open Hour');
        \Log::info('Running Command establishment_opening_hour:check_open');

        try {
            $service = new \App\Services\EstablishmentOpeningHour();
            $service->checkAutomaticOpenHour();
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            $this->error($e->getMessage());
        }

        \Log::info('Finished');
        $this->alert('Finished!');
    }
}
