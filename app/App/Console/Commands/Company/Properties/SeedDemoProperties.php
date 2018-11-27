<?php

namespace Smartville\App\Console\Commands\Company\Properties;

use Illuminate\Console\Command;
use Smartville\Domain\Company\Jobs\CreateDefaultCompanyProperties;

class SeedDemoProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:seed {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed company with demo properties';

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

        dispatch(new CreateDefaultCompanyProperties($company));
    }
}
