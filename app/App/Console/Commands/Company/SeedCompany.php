<?php

namespace Smartville\App\Console\Commands\Company;

use Illuminate\Console\Command;

class SeedCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:seed {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup company with demo data';

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
        $company = $this->argument('company');

        if (!$company) {
            return;
        }

        $this->callSilent('properties:seed', [
            'company' => $company
        ]);
    }
}
