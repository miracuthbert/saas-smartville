<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/28/2018
 * Time: 7:01 PM
 */

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register 'tenant' routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "tenant" middleware group. Now create something great!
|
*/
Route::group(['as' => 'tenant.'], function () {

    /**
     * Utility Namespace Routes
     */
    Route::group(['namespace' => 'Utility'], function () {

        /**
         * Utilities Group Routes
         */
        Route::group(['prefix' => '/utilities', 'as' => 'utilities.'], function () {

            /**
             * Utility Group Routes
             */
            Route::group(['prefix' => '/{utility}'], function () {
            });

            /**
             * Utility Invoices Group Routes
             */
            Route::group(['prefix' => '/invoices', 'as' => 'invoices.'], function () {

                /**
                 * Utility Invoices Seed Route
                 */
                Route::get('/seed', 'UtilityInvoiceSeedController@seed')->name('seed');

                /**
                 * Utility Invoices Report Routes
                 */
                Route::group(['prefix' => '/reports', 'as' => 'reports.'], function () {

                    /**
                     * Download Report Route
                     */
                    Route::get('/download', 'UtilityInvoiceReportController@download')->name('download');
                });

                /**
                 * Utility Invoice Preset Route
                 */
                Route::post('/preset', 'UtilityInvoicePresetController@setupInvoices')->name('preset');

                /**
                 * Utility Invoice Group Routes
                 */
                Route::group(['prefix' => '/{utilityInvoice}'], function () {

                    /**
                     * Utility Invoice Reminder Route
                     */
                    Route::get('/remind', 'UtilityInvoiceDueReminderController@sendReminder')->name('remind');

                    /**
                     * Utility Invoice Payment Routes
                     */
                    Route::resource('/payments', 'UtilityInvoicePaymentController', [
                        'parameters' => [
                            'payments' => 'utilityPayment'
                        ]
                    ])->except('create', 'store');

                    /**
                     * Utility Invoice Clearance Routes
                     */
                    Route::get('/clearance', 'UtilityInvoiceClearanceController@index')->name('clear');
                    Route::put('/clearance', 'UtilityInvoiceClearanceController@update')->name('clear');
                });
            });

            /**
             * Utility Invoices Resource Routes
             */
            Route::resource('/invoices', 'UtilityInvoiceController', [
                'parameters' => [
                    'invoices' => 'utilityInvoice'
                ]
            ]);
        });

        /**
         * Utilities Resource Routes
         */
        Route::resource('/utilities', 'UtilityController');
    });

    /**
     * Rent Namespace Routes
     */
    Route::group(['namespace' => 'Rent'], function () {

        /**
         * Rent Invoices Group Routes
         */
        Route::group(['prefix' => '/rent', 'as' => 'rent.'], function () {

            /**
             * Rent Invoices Report Routes
             */
            Route::group(['prefix' => '/invoices', 'as' => 'invoices.'], function () {

                /**
                 * Rent Invoices Seed Route
                 */
                Route::get('/seed', 'RentInvoiceSeedController@seed')->name('seed');

                /**
                 * Rent Invoices Report Routes
                 */
                Route::group(['prefix' => '/reports', 'as' => 'reports.'], function () {

                    /**
                     * Download Report Route
                     */
                    Route::get('/download', 'RentInvoiceReportController@download')->name('download');
                });

                /**
                 * Rent Invoice Group Routes
                 */
                Route::group(['prefix' => '/{leaseInvoice}'], function () {

                    /**
                     * Rent Invoice Reminder Route
                     */
                    Route::get('/remind', 'RentInvoiceDueReminderController@sendReminder')->name('remind');

                    /**
                     * Rent Payment Routes
                     */
                    Route::resource('/payments', 'RentInvoicePaymentController', [
                        'parameters' => [
                            'payments' => 'leasePayment'
                        ]
                    ])->except('create', 'store');

                    /**
                     * Rent Invoice Preview Route
                     */
                    Route::get('/preview', 'RentInvoicePreviewController@index')->name('preview');

                    /**
                     * Rent Invoice Clearance Routes
                     */
                    Route::get('/clearance', 'RentInvoiceClearanceController@index')->name('clear');
                    Route::put('/clearance', 'RentInvoiceClearanceController@update')->name('clear');
                });
            });

            /**
             * Rent Invoices Resource Routes
             */
            Route::resource('/invoices', 'RentInvoiceController', [
                'parameters' => [
                    'invoices' => 'leaseInvoice'
                ]
            ]);
        });
    });

    /**
     * Lease Namespace Routes
     */
    Route::group(['namespace' => 'Lease'], function () {

        /**
         * Tenants Group Routes
         */
        Route::group(['prefix' => '/tenants', 'as' => 'tenants.'], function () {

            /**
             * Tenant Vacate Route
             */
            Route::get('/{lease}/vacate', 'TenantVacateController@index')->name('vacate.index');
            Route::post('/{lease}/vacate', 'TenantVacateController@store')->name('vacate.store');

            /**
             * Resend Tenant Invitation Route
             */
            Route::post('/{tenant}/invitation/resend', 'TenantInvitationResendController@resendInvitationEmail')->name('invitation.resend');
        });

        /**
         * Tenants Resource Routes
         */
        Route::resource('/tenants', 'TenantController', [
            'parameters' => [
                'tenants' => 'lease'
            ]
        ]);
    });

    /**
     * Amenity Namespace Routes
     */
    Route::group(['namespace' => 'Amenity'], function () {

        /**
         * Amenities Resource Routes
         */
        Route::resource('/amenities', 'AmenityController');
    });

    /**
     * Property Namespace Routes
     */
    Route::group(['namespace' => 'Property'], function () {

        /**
         * Properties Group Routes
         */
        Route::group(['prefix' => '/properties', 'as' => 'properties.'], function () {

            /**
             * Property Group Routes
             */
            Route::group(['prefix' => '/{property}'], function () {

                /**
                 * Property Feature Resource Routes
                 */
                Route::apiResource('/features', 'PropertyFeatureController', [
                    'parameters' => [
                        'features' => 'propertyFeature'
                    ]
                ])->except('show');

                /**
                 * Store Property Image
                 */
                Route::post('/image', 'PropertyImageUploadController@store')->name('image.store');

                /**
                 * Property Invitation (Resource) Routes
                 */
                Route::resource('/invitations', 'TenantInvitationController', [
                    'parameters' => [
                        'invitations' => 'userInvitation'
                    ]
                ])->except('show', 'edit', 'update');

                /**
                 * (Force) Delete Property Route
                 */
                Route::delete('/delete', 'PropertyDeleteController@destroy')->name('delete');

                /**
                 * Toggle Property Status Route
                 */
                Route::put('/status', 'PropertyStatusController@update')->name('status');

                /**
                 * Create property continue
                 */
                Route::get('/create', 'PropertyController@create')->name('create');

                /**
                 * Store property
                 */
                Route::post('/', 'PropertyController@store')->name('store');
            });
        });

        /**
         * Property Resource Routes
         */
        Route::resource('/properties', 'PropertyController', [
            'names' => [
                'create' => 'properties.create.start'
            ]
        ])->except('store');
    });

    /**
     * --------------------------------------------------------------------------
     * Account Group Routes
     * --------------------------------------------------------------------------
     *
     */
    Route::group(['prefix' => '/account/manage'], function () {

        /**
         * Issue Namespace Routes
         */
        Route::group(['namespace' => 'Issue'], function () {

            /**
             * Issues Resource Routes
             */
            Route::resource('/issues', 'IssueController');
        });

        /**
         * Roles Namespace Routes
         */
        Route::group(['namespace' => 'Role'], function () {

            /**
             * Roles Group Routes
             */
            Route::group(['prefix' => '/roles', 'as' => 'roles.'], function () {

                /**
                 * Role Users Routes
                 */
                Route::get('/{companyRole}/users', 'RoleUserController@index')->name('users.index');
                Route::post('/{companyRole}/users', 'RoleUserController@store')->name('users.store');
                Route::put('/{companyRole}/{user}/users', 'RoleUserController@update')->name('users.update');
                Route::delete('/{companyRole}/users', 'RoleUserController@destroy')->name('users.destroy');
            });

            /**
             * Roles Routes
             */
            Route::resource('/roles', 'RoleController', [
                'parameters' => [
                    'roles' => 'companyRole'
                ]
            ]);
        });

        /**
         * --------------------------------------------------------------------------
         * Account Namespace Routes
         * --------------------------------------------------------------------------
         *
         */
        Route::group(['namespace' => 'Account', 'as' => 'account.'], function () {

            /**
             * Account Team Routes
             */
            Route::resource('/team', 'TeamMemberController', [
                'parameters' => [
                    'team' => 'user'
                ]
            ]);

            /**
             * Account Profile Routes
             */
            Route::get('/profile', 'CompanyController@index')->name('profile.index');
            Route::post('/profile', 'CompanyController@store')->name('profile.store');
            Route::delete('/profile', 'CompanyController@destroy')->name('profile.destroy');

            /**
             * Account Overview Route
             */
            Route::get('/', 'AccountController@index')->name('index');
        });
    });

    /**
     * --------------------------------------------------------------------------
     * Dashboard Group Routes
     * --------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/dashboard'], function () {

        /**
         * Issues Route
         */
        Route::get('/issues', 'TenantIssueController@index')->name('dashboard.issues.index');

        /**
         * Dashboard Route
         */
        Route::get('/', 'DashboardController@index')->name('dashboard');
    });


    /**
     * Tenant switch route
     *
     * All other tenant routes should go above this one
     */
    Route::get('/{company}', 'TenantSwitchController@switch')->name('switch');
});
