<?php

namespace Smartville\Http\ViewComposers;

use DateTimeZone;
use Illuminate\View\View;

class TimezonesComposer
{
    /**
     * Implements timezones collection.
     *
     * @var $timezones
     */
    private $timezones;

    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        if (!$this->timezones) {
            $this->timezones = collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))->mapWithKeys(function ($timezone) {
                return [$timezone => $timezone];
            })->all();
        }

        return $view->with('timezones', $this->timezones);
    }
}