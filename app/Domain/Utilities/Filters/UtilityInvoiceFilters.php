<?php

namespace Smartville\Domain\Utilities\Filters;

use Smartville\App\Filters\Dates\ClearedFilter;
use Smartville\App\Filters\Dates\DueFilter;
use Smartville\App\Filters\Dates\SentFilter;
use Smartville\App\Filters\FiltersAbstract;
use Smartville\App\Filters\Ordering\CreatedOrder;
use Smartville\App\Filters\Status\InvoiceStatusFilter;
use Smartville\App\Filters\Tenant\TenantFilter;
use Smartville\Domain\Utilities\Models\Utility;

class UtilityInvoiceFilters extends FiltersAbstract
{
    /**
     * A list of filters.
     *
     * @var array
     */
    protected $filters = [
        'order_amount' => AmountOrder::class,
        'created' => CreatedOrder::class,
        'sent' => SentFilter::class,
        'due' => DueFilter::class,
        'cleared' => ClearedFilter::class,
        'utility' => UtilityFilter::class,
        'status' => InvoiceStatusFilter::class,
        'tenant' => TenantFilter::class,
    ];

    /**
     * A list of default filters.
     *
     * @var array
     */
    protected $defaultFilters = [
        'created' => 'desc',
    ];

    /**
     * A list of filters map.
     *
     * @return array
     */
    public static function mappings()
    {
        $map = [
            'utility' => [
                'map' => Utility::with('company')->where('usable', true)
                    ->get()
                    ->pluck('name', 'slug'),
                'heading' => 'Utilities'
            ],
            'status' => [
                'map' => [
                    'draft' => 'Drafts',
                    'queued' => 'Queued (Awaiting to be sent)',
                    'sent' => 'Sent',
                    'past_due' => 'Past Due',
                    'cleared' => 'Cleared',
                ],
                'heading' => 'Status'
            ],
            'order_amount' => [
                'map' => [
                    'desc' => 'Max - Min',
                    'asc' => 'Min - Max'
                ],
                'heading' => 'Amount'
            ],
            'created' => [
                'map' => [
                    'desc' => 'Latest',
                    'asc' => 'Older'
                ],
                'heading' => 'Date Generated'
            ],
            'sent' => [
                'map' => [
                    'today' => 'Today',
                    'week' => 'This week',
                    'month' => 'This month',
                    'year' => 'This year',
                ],
                'heading' => 'Date Sent'
            ],
            'due' => [
                'map' => [
                    'last_week' => 'Last Week',
                    'today' => 'Today',
                    'week' => 'This week',
                    'next_week' => 'Next week',
                    'month' => 'This month',
                ],
                'heading' => 'Date Due'
            ],
            'cleared' => [
                'map' => [
                    'today' => 'Today',
                    'week' => 'This week',
                    'month' => 'This month',
                    'year' => 'This year',
                ],
                'heading' => 'Date Cleared'
            ],
        ];

        return $map;
    }
}
