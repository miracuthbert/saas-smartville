<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/12/2018
 * Time: 9:40 PM
 */

namespace Smartville\App\Traits\Eloquent\Dates;

use Carbon\Carbon;

trait UsesLocalDates
{
    /**
     * Localize a date to users timezone.
     *
     * @param null $dateField
     * @return Carbon
     */
    public function localize($dateField = null)
    {
        $dateValue = is_null($this->{$dateField}) ? Carbon::now() : $this->{$dateField};

        return $this->inModelTimezone($dateValue);
    }

    /**
     * Change timezone of a carbon date.
     *
     * @param $dateValue
     * @return Carbon
     */
    protected function inModelTimezone($dateValue): Carbon
    {
        $timezone = optional(request()->user())->timezone;

        $timezone = $timezone ?? config('app.timezone');

        return $this->asDateTime($dateValue)->timezone($timezone);
    }
}