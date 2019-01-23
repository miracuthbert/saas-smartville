<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Auth Routes
 */
Route::group(['namespace' => 'Auth\Controllers'], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

    /**
     * Invitation Group Routes
     */
    Route::group(['prefix' => '/auth/invitations', 'middleware' => ['guest'], 'as' => 'auth.invitations.'], function () {

        /**
         * Tenant Invitation Setup
         */
        Route::get('/company/{company}/{invitation}', 'AcceptCompanyInvitationController@setupCompanyInvitation')->name('company.setup');

        /**
         * Tenant Invitation Setup
         */
        Route::get('/tenant/{property}/{invitation}', 'AcceptTenantInvitationController@setupTenantInvitation')->name('tenant.setup');
    });

    /**
     * Activation Group Routes
     */
    Route::group(['prefix' => '/activation', 'middleware' => ['guest'], 'as' => 'activation.'], function () {

        // resend index
        Route::get('/resend', 'ActivationResendController@index')->name('resend');

        // resend store
        Route::post('/resend', 'ActivationResendController@store')->name('resend.store');

        // activation
        Route::get('/{confirmation_token}', 'ActivationController@activate')->name('activate');
    });

    /**
     * Two Factor Login Group Routes
     */
    Route::group(['prefix' => '/login/twofactor', 'middleware' => ['guest'], 'as' => 'login.twofactor.'], function () {

        // index
        Route::get('/', 'TwoFactorLoginController@index')->name('index');

        // store
        Route::post('/', 'TwoFactorLoginController@verify')->name('verify');
    });
});

/**
 * Home Routes
 */
Route::group(['namespace' => 'Home\Controllers'], function () {

    // index
//    Route::get('/welcome', 'HomeController@index')->name('home');
});

/**
 * Utility Namespace Routes
 */
Route::group(['namespace' => 'Utility\Controllers'], function () {

    /**
     * Utilities Settings Map Route
     */
    Route::get('/utilities/settings/map', 'UtilitySettingsMapController')->name('utilities.settings.map');
});

/**
 * Rent Namespace Routes
 */
Route::group(['namespace' => 'Rent\Controllers'], function () {

    /**
     * Rent Settings Map Route
     */
    Route::get('/rent/settings/map', 'RentSettingsMapController')->name('rent.settings.map');
});

/**
 * Comments Namespace Routes
 */
Route::group(['namespace' => 'Comment\Controllers'], function () {

    /**
     * Comment Routes
     */
    Route::group(['prefix' => '/comments/{comment}', 'as' => 'comments.'], function () {

        /**
         * Comment Reply Routes
         */
        Route::resource('/replies', 'CommentReplyController')->only('store');
    });

    /**
     * Comments Routes
     */
    Route::resource('/comments', 'CommentController')->only('update', 'destroy');
});

/**
 * Issues Namespace Routes
 */
Route::group(['namespace' => 'Issue\Controllers'], function () {

    /**
     * Issue Routes
     */
    Route::group(['prefix' => '/issues/{issue}', 'as' => 'issues.'], function () {

        /**
         * Issue Comments Routes
         */
        Route::resource('/comments', 'IssueCommentController')->only('index', 'store');

        /**
         * Issue Close Route
         */
        Route::put('/close', 'IssueCloseController@update')->name('close');
    });

    /**
     * Issues Routes
     */
    Route::resource('/issues', 'IssueController')->only('update', 'destroy');
});

/**
 * Support Routes
 */
Route::group(['namespace' => 'Support\Controllers', 'as' => 'support.'], function () {

    /**
     * Contact Routes
     */
    Route::resource('/contact', 'ContactController');
});

/**
 * Plans Routes
 */
Route::group(['namespace' => 'Subscription\Controllers'], function () {

    /**
     * Plans Group Routes
     */
    Route::group(['prefix' => '/plans', 'middleware' => ['subscription.inactive'], 'as' => 'plans.'], function () {

        // teams index
        Route::get('/teams', 'PlanTeamController@index')->name('teams.index');
    });

    /**
     * Plans Resource Routes
     */
    Route::resource('/plans', 'PlanController', [
        'only' => [
            'index',
            'show'
        ]
    ])->middleware(['subscription.inactive']);

    /**
     * Subscription Resource Routes
     */
    Route::resource('/subscription', 'SubscriptionController', [
        'only' => [
            'index',
            'store'
        ]
    ])->middleware(['auth.register', 'subscription.inactive']);
});

/**
 * Developer Routes.
 *
 * Handles developer routes.
 */
Route::group(['prefix' => '/developers', 'middleware' => ['auth'], 'namespace' => 'Developer\Controllers', 'as' => 'developer.'], function () {

    // index
    Route::get('/dashboard', 'DeveloperController@index')->name('index');
});

/**
 * Subscription: active Routes
 */
Route::group(['middleware' => ['subscription.active']], function () {
});

/**
 * Account Group Routes.
 *
 * Handles user's account routes.
 */
Route::group(['prefix' => '/account', 'middleware' => ['auth'], 'as' => 'account.'], function () {

    /**
     * Notifications Routes
     */
    Route::group(['namespace' => 'Notification\Controllers'], function () {
        Route::get('/notifications/unread', 'NotificationUnreadController@unread')->name('notifications.unread');

        Route::apiResource('/notifications', 'NotificationController');
    });

    /**
     * Account Namespace Routes
     */
    Route::group(['namespace' => 'Account\Controllers'], function () {

        /**
         * Dashboard Issues Route
         */
        Route::get('/dashboard/issues', 'IssueController@index')->name('dashboard.issues.index');

        /**
         * Notifications Route
         */
        Route::get('/dashboard/notifications', 'NotificationController@index')->name('dashboard.notifications.index');

        /**
         * Dashboard Route
         */
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        /**
         * Issues Routes
         */
        Route::group(['namespace' => 'Issue'], function () {
//            Route::get('/issues/unread', 'NotificationUnreadController@unread')->name('issues.unread');

            Route::resource('/issues', 'IssueController');
        });

        /**
         * Invoice Namespace Routes
         */
        Route::group(['namespace' => 'Payment', 'prefix' => '/payments', 'as' => 'payments.'], function () {

            /**
             * Utilities Payment Routes
             */
            Route::group(['prefix' => '/utilities', 'as' => 'utilities.'], function () {
                Route::get('/{utilityPayment}/pdf/download', 'UtilityPaymentDownloadController@downloadPdf')->name('pdf.download');
                Route::get('/{utilityPayment}', 'UtilityPaymentController@show')->name('show');
                Route::get('/', 'UtilityPaymentController@index')->name('index');
            });

            /**
             * Rent Payment Routes
             */
            Route::group(['prefix' => '/rent', 'as' => 'rent.'], function () {
                Route::get('/{leasePayment}/pdf/download', 'RentPaymentDownloadController@downloadPdf')->name('pdf.download');
                Route::get('/{leasePayment}', 'RentPaymentController@show')->name('show');
                Route::get('/', 'RentPaymentController@index')->name('index');
            });
        });

        /**
         * Invoice Namespace Routes
         */
        Route::group(['namespace' => 'Invoice', 'prefix' => '/invoices', 'as' => 'invoices.'], function () {

            /**
             * Utility Invoices Group Routes
             */
            Route::group(['prefix' => '/utilities', 'as' => 'utilities.'], function () {

                /**
                 * Utility Invoice Preview Route
                 */
                Route::get('/{utilityInvoice}/preview', 'UtilityInvoicePreviewController@preview')->name('preview');

                /**
                 * Rent Invoice Download Routes
                 */
                Route::get('/{utilityInvoice}/pdf/download', 'UtilityInvoiceDownloadController@downloadPdf')->name('pdf.download');
            });

            /**
             * Utility Invoices Resource Routes
             */
            Route::resource('/utilities', 'UtilityInvoiceController', [
                'parameters' => [
                    'utilities' => 'utilityInvoice'
                ]
            ])->except('create', 'store', 'destroy');

            /**
             * Rent Invoices Group Routes
             */
            Route::group(['prefix' => '/rent', 'as' => 'rent.'], function () {

                /**
                 * Rent Invoice Download Routes
                 */
                Route::get('/{leaseInvoice}/pdf/download', 'RentInvoiceDownloadController@downloadPdf')->name('pdf.download');
            });

            /**
             * Rent Invoices Resource Routes
             */
            Route::resource('/rent', 'RentInvoiceController', [
                'parameters' => [
                    'rent' => 'leaseInvoice'
                ]
            ])->except('create', 'store', 'destroy');
        });

        /**
         * Leases Namespace Routes
         */
        Route::group(['namespace' => 'Lease'], function () {

            /**
             * Lease Group Routes
             */
            Route::group(['prefix' => '/leases', 'as' => 'leases.'], function () {

                /**
                 * Resume Lease Setup route
                 */
                Route::get('/setup/{property}/{invitation}/resume', 'LeaseSetupController@resumeLeaseSetup')->name('setup.resume');
            });

            /**
             * Lease Resource Routes
             */
            Route::resource('/leases', 'LeaseController')->except('create', 'edit');
        });

        /**
         * Company Namespace Routes
         */
        Route::group(['namespace' => 'Company'], function () {

            /**
             * Company Group Routes
             */
            Route::group(['prefix' => '/companies', 'as' => 'companies.'], function () {

                /**
                 * Resume Company User Setup route
                 */
                Route::get('/setup/{company}/{invitation}/resume', 'CompanyUserSetupController@resumeUserSetup')->name('setup.resume');
            });

            /**
             * Companies Resource Routes
             */
            Route::resource('/companies', 'CompanyController', [
                'only' => [
                    'index',
                    'create',
                    'store',
                    'destroy'
                ]
            ]);
        });

        /**
         * Account Overview Route
         */
        Route::get('/', 'AccountController@index')->name('index');

        /**
         * Profile Routes
         */
        Route::get('/profile', 'ProfileController@index')->name('profile.index');
        Route::post('/profile', 'ProfileController@store')->name('profile.store');

        /**
         * Password Routes
         */
        Route::get('/password', 'PasswordController@index')->name('password.index');
        Route::post('/password', 'PasswordController@store')->name('password.store');

        /**
         * Deactivate Account Routes
         */
        Route::get('/deactivate', 'DeactivateController@index')->name('deactivate.index');
        Route::post('/deactivate', 'DeactivateController@store')->name('deactivate.store');

        /**
         * Two factor
         */
        Route::group(['prefix' => '/twofactor', 'as' => 'twofactor.'], function () {
            /**
             * Two Factor Index Route
             */
            Route::get('/', 'TwoFactorController@index')->name('index');

            /**
             * Two Factor Store Route
             */
            Route::post('/', 'TwoFactorController@store')->name('store');

            /**
             * Two Factor Verify Route
             */
            Route::post('/verify', 'TwoFactorController@verify')->name('verify');

            /**
             * Two Factor Verify Delete
             */
            Route::delete('/', 'TwoFactorController@destroy')->name('destroy');
        });

        /**
         * Tokens
         */
        Route::group(['prefix' => '/tokens', 'as' => 'tokens.'], function () {
            // personal access token index
            Route::get('/', 'PersonalAccessTokenController@index')->name('index');
        });

        /**
         * Subscription
         */
        Route::group(['prefix' => '/subscription', 'namespace' => 'Subscription',
            'middleware' => ['subscription.owner'], 'as' => 'subscription.'], function () {
            /**
             * Cancel
             *
             * Accessed if there is an active subscription.
             */
            Route::group(['prefix' => '/cancel', 'middleware' => ['subscription.notcancelled'], 'as' => 'cancel.'], function () {
                // cancel subscription index
                Route::get('/', 'SubscriptionCancelController@index')->name('index');

                // cancel subscription
                Route::post('/', 'SubscriptionCancelController@store')->name('store');
            });

            /**
             * Resume
             *
             * Accessed if subscription is cancelled but not expired.
             */
            Route::group(['prefix' => '/resume', 'middleware' => ['subscription.cancelled'], 'as' => 'resume.'], function () {
                // resume subscription index
                Route::get('/', 'SubscriptionResumeController@index')->name('index');

                // resume subscription
                Route::post('/', 'SubscriptionResumeController@store')->name('store');
            });

            /**
             * Swap Subscription
             *
             * Accessed if there is an active subscription.
             */
            Route::group(['prefix' => '/swap', 'middleware' => ['subscription.notcancelled'], 'as' => 'swap.'], function () {
                // swap subscription index
                Route::get('/', 'SubscriptionSwapController@index')->name('index');

                // swap subscription store
                Route::post('/', 'SubscriptionSwapController@store')->name('store');
            });

            /**
             * Card
             */
            Route::group(['prefix' => '/card', 'middleware' => ['subscription.customer'], 'as' => 'card.'], function () {
                // card index
                Route::get('/', 'SubscriptionCardController@index')->name('index');

                // card store
                Route::post('/', 'SubscriptionCardController@store')->name('store');
            });

            /**
             * Team
             */
            Route::group(['prefix' => '/team', 'middleware' => ['subscription.team'], 'as' => 'team.'], function () {
                // team index
                Route::get('/', 'SubscriptionTeamController@index')->name('index');

                // team update
                Route::put('/', 'SubscriptionTeamController@update')->name('update');

                // store team member
                Route::post('/member', 'SubscriptionTeamMemberController@store')->name('member.store');

                // destroy team member
                Route::delete('/member/{user}', 'SubscriptionTeamMemberController@destroy')->name('member.destroy');
            });
        });
    });
});

/**
 * Admin Group Routes
 */
Route::group(['prefix' => '/admin', 'namespace' => 'Admin\Controllers', 'as' => 'admin.'], function () {

    /**
     * Impersonate destroy
     */
    Route::delete('/users/impersonate', 'User\UserImpersonateController@destroy')->name('users.impersonate.destroy');

    /**
     * Admin Role Middleware Routes
     */
    Route::group(['middleware' => ['auth', 'role:admin']], function () {

        /**
         * Dashboard Route
         */
        Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');

        /**
         * Tutorial Namespace Routes
         */
        Route::group(['namespace' => 'Tutorial'], function () {

            /**
             * Tutorials Group Routes
             */
            Route::group(['prefix' => '/tutorials', 'as' => 'tutorials.'], function () {

                /**
                 * Toggle Tutorial Status Route
                 */
                Route::put('/{tutorial}/status', 'TutorialStatusController@update')->name('status');
            });

            /**
             * Tutorials Resource Routes
             */
            Route::resource('/tutorials', 'TutorialController');
        });

        /**
         * Feature Namespace Routes
         */
        Route::group(['namespace' => 'Feature'], function () {

            /**
             * Features Group Routes
             */
            Route::group(['prefix' => '/features', 'as' => 'features.'], function () {

                /**
                 * Toggle Feature Status Route
                 */
                Route::put('/{feature}/status', 'FeatureStatusController@update')->name('status');
            });

            /**
             * Features Resource Routes
             */
            Route::resource('/features', 'FeatureController');
        });

        /**
         * Page Namespace Routes
         */
        Route::group(['namespace' => 'Page'], function () {

            /**
             * Pages Group Routes
             */
            Route::group(['prefix' => '/pages', 'as' => 'pages.'], function () {

                /**
                 * Toggle Page Status Route
                 */
                Route::put('/{page}/status', 'PageStatusController@update')->name('status');
            });

            /**
             * Pages Resource Routes
             */
            Route::resource('/pages', 'PageController');
        });

        /**
         * Category Namespace Routes
         */
        Route::group(['namespace' => 'Category'], function () {

            /**
             * Categories Group Routes
             */
            Route::group(['prefix' => '/categories', 'as' => 'categories.'], function () {

                /**
                 * Toggle Category Status Route
                 */
                Route::put('/{category}/status', 'CategoryStatusController@update')->name('status');
            });

            /**
             * Categories Resource Routes
             */
            Route::resource('/categories', 'CategoryController');
        });

        /**
         * Currency Namespace Routes
         */
        Route::group(['namespace' => 'Currency'], function () {

            /**
             * Currency Group Routes
             */
            Route::group(['prefix' => '/currencies', 'as' => 'currencies.'], function () {

                /**
                 * Toggle Currency Status Route
                 */
                Route::put('/{currency}/status', 'CurrencyStatusController@update')->name('status');
            });

            /**
             * Currency Resource Routes
             */
            Route::resource('/currencies', 'CurrencyController');
        });

        /**
         * User Namespace Routes
         */
        Route::group(['namespace' => 'User'], function () {

            /**
             * Users Group Routes
             */
            Route::group(['prefix' => '/users', 'as' => 'users.'], function () {

                /**
                 * User Impersonate Routes
                 */
                Route::group(['prefix' => '/impersonate', 'as' => 'impersonate.'], function () {
                    // index
                    Route::get('/', 'UserImpersonateController@index')->name('index');

                    // store
                    Route::post('/', 'UserImpersonateController@store')->name('store');
                });


                /**
                 * User Group Routes
                 */
                Route::group(['prefix' => '/{user}'], function () {
                    Route::resource('/roles', 'UserRoleController', [
                        'except' => [
                            'show',
                            'edit',
                        ]
                    ]);
                });
            });

            /**
             * Permissions Group Routes
             */
            Route::group(['prefix' => '/permissions', 'as' => 'permissions.'], function () {

                /**
                 * Permissions Seed Route
                 */
                Route::get('/seed', 'PermissionSeedController@seed')->name('seed');

                /**
                 * Role Group Routes
                 */
                Route::group(['prefix' => '/{permission}'], function () {

                    // toggle status
                    Route::put('/status', 'PermissionStatusController@toggleStatus')->name('toggleStatus');
                });
            });

            /**
             * Permissions Resource Routes
             */
            Route::resource('/permissions', 'PermissionController');

            /**
             * Roles Group Routes
             */
            Route::group(['prefix' => '/roles', 'as' => 'roles.'], function () {

                /**
                 * Role Group Routes
                 */
                Route::group(['prefix' => '/{role}'], function () {

                    // toggle status
                    Route::put('/status', 'RoleStatusController@toggleStatus')->name('toggleStatus');

                    // revoke users access
                    Route::put('/revoke', 'RoleUsersDisableController@revokeUsersAccess')->name('revokeUsersAccess');

                    /**
                     * Permissions Resource Routes
                     */
                    Route::resource('/permissions', 'RolePermissionController', [
                        'only' => [
                            'index',
                            'store',
                            'destroy',
                        ]
                    ]);
                });
            });

            /**
             * Roles Resource Routes
             */
            Route::resource('/roles', 'RoleController');

            /**
             * Users Resource Routes
             */
            Route::resource('/users', 'UserController');
        });
    });
});

/**
 * Webhooks Routes
 */
Route::group(['namespace' => 'Webhook\Controllers'], function () {

    // Stripe Webhook
    Route::post('/webhooks/stripe', 'StripeWebhookController@handleWebhook');
});