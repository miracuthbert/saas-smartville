<?php

namespace Smartville\Domain\Company\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartville\Domain\Categories\Models\Category;
use Smartville\Domain\Company\Models\Company;

class CreateDefaultCompanyProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $company
     */
    protected $company;

    /**
     * Create a new job instance.
     *
     * @param $company
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->company = Company::find($this->company);

        if (!$this->company->exists) {
            return;
        }

        $properties = [
            'Sunset View',
            'Sunrise View',
            'Bird\'s View',
        ];

        $category = Category::with('children')->where('slug', 'properties')->first();

        $properties = collect($properties)->map(function ($property) use ($category) {
            $prop = factory(\Smartville\Domain\Properties\Models\Property::class)->make(array(
                'name' => $property,
                'slug' => str_slug(($this->company->short_name . ' ' . $property)),
                'currency' => $this->company->currency,
            ));
            $prop->category()->associate($category->children->random());

            return $prop;
        });

        $this->company->properties()->saveMany($properties->all());
    }
}
