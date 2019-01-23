<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 1/21/2019
 * Time: 5:27 PM
 */

namespace Smartville\App\Settings;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class TenantUtilitySettings extends Valuestore
{
    /**
     * The default tenant utility settings.
     *
     * @var array
     */
    public static $defaults = [
        'auto-generate' => false,
        'invoice-template' => 'default',
    ];

    /**
     * Utilities settings map.
     *
     * @return array
     */
    public static function map()
    {
        $map = [
            'auto-generate' => [
                'label' => 'Auto generate invoices',
                'values' => [
                    'true' => 'Yes',
                    'false' => 'No (You will only be notified)'
                ],
            ],
            'invoice-template' => [
                'label' => 'Invoice template',
                'values' => static::getInvoiceTemplates(),
            ],
        ];

        return $map;
    }

    /**
     * Utilities invoice templates.
     *
     * @return static
     */
    protected static function getInvoiceTemplates()
    {
        $cmsPath = resource_path('views/invoices/utilities/pdf');

        $layouts = collect(File::files($cmsPath, true))->mapWithKeys(function ($layout, $key) {
            $name = str_replace(".blade.php", '', $layout->getFilename());

            return [$name => title_case($name)];
        });

        return $layouts->toArray();
    }
}