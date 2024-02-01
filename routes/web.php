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

/* Auth routes */

use App\Http\Controllers\CSVUploadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UpdatesController;

Auth::routes();

/* Dashboard Controller routes */

Route::get('/request-login', [DashboardController::class, 'requestLogin'])
    ->name('request-login')
    ->withoutMiddleware('auth');

Route::post('/request-login', [DashboardController::class, 'sendRequest'])
    ->name('send-request')
    ->withoutMiddleware('auth');

Route::middleware('auth')->group(function () {
    Route::controller('DashboardController')->group(function () {
        Route::get('/', 'index')->name('dashboard.base');
        Route::get('/dashboard', 'index')->name('dashboard');
        /* Notifications Routes */
        Route::prefix('notifications')->group(function () {
            Route::get('/', 'showNotifications')->name('notifications');
            Route::get('/delete', 'executeDeleteNotifications')->name(
                'notifications.delete',
            );
            Route::get('/readAll', 'readAllNotifications')->name(
                'notifications.read',
            );
            Route::get('/mark-read/{notification}', 'readNotifications')->name(
                'notifications.mark-read',
            );
            Route::get(
                '/mark-unread/{notification}',
                'unreadNotifications',
            )->name('notifications.mark-unread');
        });
    });

    /* Order Controller Routes */

    Route::controller(OrderController::class)->group(function () {
        Route::get('/create-order', 'create')->name('create_order');
        Route::get('/manage-deliveries', 'showManageDeliveries')->name(
            'manage_deliveries',
        );
        Route::get('/completed-orders', 'completedOrders')->name(
            'completed_orders',
        );
        Route::get('/order-bank', 'showOrderBank')->name('order_bank');
        Route::prefix('export')
            ->name('export.')
            ->group(function () {
                Route::get(
                    '/brokers-download/{broker}',
                    'broker_orders_export',
                )->name('brokers_orders_export');
            });
        Route::get('/order/recycle-bin', 'recycle')->name('order.recycle-bin');
        Route::prefix('orders')
            ->name('order.')
            ->group(function () {
                Route::get('/view/{order}', 'show')->name('show');
                Route::get('/duplicate/{order}', 'duplicate')->name(
                    'duplicate',
                );
                Route::get('/edit/{order}', 'edit')->name('edit');
                Route::get('/{vehicle}', 'showReserveOrder')->name('reserve');
                Route::get('/pdf/{order}', 'downloadPDF')->name('pdf');
                Route::get('/delete/{order}', 'destroy')->name('destroy');
                Route::get('/force-delete/{order}', 'forceDelete')->name(
                    'force-delete',
                );
            });
    });

    Route::prefix('deliveries')
        ->controller(DeliveriesController::class)
        ->name('delivery.')
        ->group(function () {
            Route::get('create/{order}', 'create')->name('create');
            Route::get('show/{delivery}', 'show')->name('show');
            Route::get('accept/{delivery}', 'accept')->name('accept');
            Route::get('cancel/{delivery}', 'cancel')->name('cancel');
            Route::get('edit/{delivery}', 'edit')->name('edit');
            Route::get('test-email', 'testDeliveryEmails')->name('test-email');
        });

    /* Reservation Controller Routes */
    Route::controller(ReservationController::class)
        ->name('reservation.')
        ->group(function () {
            Route::get('/reserve-vehicle/{vehicle}', 'create')->name('create');
            Route::get('/reservations', 'index')->name('index');
            Route::prefix('reservations')->group(function () {
                Route::get('/{reservation}/extend', 'extend')->name('extend');
                Route::get('/{user}/toggle', 'toggle')->name('toggle');
                Route::get('/{vehicle}/admin', 'admin_create')->name('admin');
            });
        });

    /* Vehicle Controller Routes */
    Route::controller('VehicleController')->group(function () {
        Route::get('/create-vehicle', 'create')->name('create_vehicle');
        Route::get('/pipeline', 'showLedenStock')->name('pipeline');
        Route::get('/ringfenced-stock', 'showRingFencedStock')->name(
            'ring_fenced_stock',
        );
        Route::get('/ford-pipeline', 'showFordPipeline')->name('pipeline.ford');
        Route::prefix('vehicle')->group(function () {
            Route::name('vehicle.')->group(function () {
                Route::get('otr/{vehicle}', 'request_otr')->name('request-otr');
                Route::get('/delete/{vehicle}', 'destroy')->name('delete');
                Route::get('/view/{vehicle}', 'show')->name('show');
                Route::get('/edit/{vehicle}', 'edit')->name('edit');
                Route::get('/recycle-bin', 'recycle')->name('recycle_bin');
                Route::get('/force-delete/{vehicle}', 'forceDelete')->name(
                    'force-delete',
                );
                Route::get('/restore/{vehicle}', 'restore')->name('restore');
                Route::get('/search/', 'searchVehicles')->name('search');
            });
            Route::name('export.')
                ->prefix('export')
                ->group(function () {
                    Route::get(
                        '/brokers-stock/{broker}',
                        'brokers_stock_export',
                    )->name('brokers_stock_export');
                    Route::get(
                        '/{export}/dashboard-download',
                        'dashboard_download',
                    )->name('dashboardDownload');
                    Route::get('/foexport/', 'factory_order_export')->name(
                        'factory_order',
                    );
                    Route::get('/eurovhcexport/', 'europe_vhc_export')->name(
                        'europe_v_h_c',
                    );
                    Route::get('/ukvhcexport/', 'uk_vhc_export')->name(
                        'u_k_v_h_c',
                    );
                    Route::get('/instockexport/', 'in_stock_export')->name(
                        'in_stock',
                    );
                    Route::get(
                        '/instockregisteredexport/',
                        'in_stock_registered_export',
                    )->name('in_stock_registered');
                    Route::get(
                        '/instockdealerexport/',
                        'in_stock_dealer_export',
                    )->name('in_stock_awaiting_dealer_options');
                    Route::get(
                        '/readyfordeliveryexport/',
                        'ready_for_delivery_export',
                    )->name('ready_for_delivery');
                    Route::get(
                        '/deliverybooked/',
                        'delivery_booked_export',
                    )->name('delivery_booked');
                    Route::get('/awaitingship/', 'awaiting_ship_export')->name(
                        'awaiting_ship',
                    );
                    Route::get('/atconverter/', 'at_converter_export')->name(
                        'at_converter',
                    );
                    Route::get('/damaged/', 'damaged_export')->name('damaged');
                    Route::get(
                        '/dealertransfer',
                        'dealer_transfer_export',
                    )->name('dealer_transfer');
                    Route::get('/orderinquery', 'query_export')->name(
                        'order_in_query',
                    );
                });
        });
    });

    /* ReportingController routes */
    Route::prefix('reporting')
        ->controller('ReportingController')
        ->group(function () {
            Route::get('/', 'showReporting')->name('reporting');
            Route::get('/new', 'index')->name('new-reporting');
            Route::get('/monthly-{report}/{year}/{month}', 'monthlyDownload');
            Route::get(
                '/quarterly-{report}/{year}/{quarter}',
                'quarterlyDownload',
            );
            Route::get(
                '/registeredMonthly/{month}/{year}',
                'registeredMonth',
            )->name('monthly-registered');
            Route::get(
                '/registeredQuarterly/{quarter?}/{year?}',
                'registeredQuarter',
            )->name('quarter-registered');
            Route::get('/weekly-{report}/{year}/{quarter}', 'weeklyDownload');
            Route::get('/financeMonthly/{month}/{year}', 'financeMonth')->name(
                'monthly-finance',
            );
            Route::get(
                '/financeQuarterly/{quarter}/{year}',
                'financeQuarter',
            )->name('quarter-finance');
        });

    /* CSVUploadController routes */
    Route::controller('CSVUploadController')->group(function () {
        Route::get('/csv-upload', 'showCsvUpload')->name('csv_upload');
        Route::get('/rf-upload', 'showRingFenceUpload')->name('rf_upload');
        Route::get('/fit-option-upload', 'showFitOptionUpload')->name(
            'fit_option_upload',
        );
        Route::post('/csv-upload', 'executeCsvUpload')->name(
            'csv_upload.import',
        );
        Route::post('/rf-upload', 'executeRfUpload')->name('rf_upload.import');
        Route::post('/import_parse', 'parseFitOptionImport')->name(
            'import_parse',
        );
        Route::post('/import_process', 'processFitOptionImport')->name(
            'import_process',
        );
    });

    /* Customer Controller Routes */
    Route::get('/customers', 'CustomerController@index')->name(
        'customer.index',
    );

    /* MessagesController routes */
    Route::controller('MessagesController')
        ->prefix('messages')
        ->group(function () {
            Route::get('/', 'showMessages')->name('messages');
            Route::name('message.')->group(function () {
                Route::get('/new', 'showNewMessage')->name('new');
                Route::post('/new', 'executeNewMessage')->name('send');
                Route::get('/{message_group_id}', 'showMessage')->name('view');
                Route::post('/{message_group_id}', 'executeReplyMessage')->name(
                    'reply',
                );
            });
        });

    /* ProfileController routes */
    Route::controller('ProfileController')->group(function () {
        Route::get('/profile', 'showProfile')->name('profile');
        Route::post('/profile', 'executeUpdateProfile')->name('profile.update');
        Route::prefix('user-management')
            ->name('user.')
            ->group(function () {
                Route::get('/add', 'showAddUser')->name('add');
                Route::post('/add', 'executeAddUser')->name('create');
                Route::post('/edit/{user}', 'storeEditUser')->name('update');
            });

        Route::middleware('can:admin')->group(function () {
            Route::get('/user-management', 'showUserManager')->name(
                'user_manager',
            );
            Route::get('/user-management/edit/{user}', 'showEditUser')->name(
                'user.edit',
            );
            Route::get(
                '/user-management/disable/{user}',
                'toggleDisabled',
            )->name('toggle.user.disabled');
            Route::get('/user-management/delete/{user}', 'delete')->name(
                'user.delete',
            );
        });
    });

    Route::get('/companies', 'CompanyController@index')
        ->name('company_manager')
        ->middleware('can:admin');
    Route::get('/companies/add', 'CompanyController@create')
        ->name('company.add')
        ->middleware('can:admin');
    Route::post('/companies/add', 'CompanyController@store')->name(
        'company.create',
    );
    Route::get('/companies/edit/{company}', 'CompanyController@edit')
        ->name('company.edit')
        ->middleware('can:admin');
    Route::post('/companies/edit/{company}', 'CompanyController@update')->name(
        'company.update',
    );

    /* Vehicle Meta CRUD Routes
     * Added 04.05.2021 - By Link
     */

    Route::prefix('manage/vehiclemeta')
        ->name('meta.')
        ->group(function () {
            Route::controller('VehicleMetaController')->group(function () {
                // Colour
                Route::get('colour', 'colourIndex')->name('colour.index');
                // Derivative
                Route::get('derivative', 'derivativeIndex')->name(
                    'derivative.index',
                );
                // Engine
                Route::get('engine', 'engineIndex')->name('engine.index');
                // Fuel
                Route::get('fuel', 'fuelIndex')->name('fuel.index');
                // Transmission
                Route::get('transmission', 'transmissionIndex')->name(
                    'transmission.index',
                );
                // Trim
                Route::get('trim', 'trimIndex')->name('trim.index');
                // Type
                Route::get('type', 'typeIndex')->name('type.index');
                // Make
                Route::get('make', 'makeIndex')->name('make.index');
            });
            Route::controller('FitOptionsController')->group(function () {
                // Factory Fit Options
                Route::get('factoryfit', 'factoryFitIndex')->name(
                    'factoryfit.index',
                );
                // Dealer Fit Options
                Route::get('dealerfit', 'dealerFitIndex')->name(
                    'dealerfit.index',
                );
            });
        });

    /*
     * Data Management Routes
     * Added by Link Digital
     *
     */
    Route::get('/link/vehicle-date-clean-up', 'VehicleController@cleanDates');
    Route::get(
        '/link/vehicle_provisional_date',
        'VehicleController@checkProvisionalDates',
    );

    Route::get('/link/order-date-clean-up', 'OrderController@date_cleanup');
    Route::get('/link/invoice-date-clean-up', 'InvoiceController@cleanDates');

    /*
     * News Updates Controller
     */

    Route::controller(UpdatesController::class)
        ->name('updates.')
        ->prefix('updates')
        ->group(function () {
            Route::get('create', 'create')->name('create');
        });

    Route::controller(LocationsController::class)
        ->name('locations.')
        ->prefix('locations')
        ->group(function () {
            Route::get('create', 'create')->name('create');
        });

    Route::prefix('finance')
        ->name('finance.')
        ->group(function () {
            Route::get(
                'finance-type/index',
                'Finance\FinanceTypesController@index',
            )->name('finance-type.index');
            Route::get(
                'finance-type/create',
                'Finance\FinanceTypesController@create',
            )->name('finance-type.create');
            Route::get(
                'maintenance/index',
                'Finance\MaintenancesController@index',
            )->name('maintenance.index');
            Route::get('term/index', 'Finance\TermsController@index')->name(
                'term.index',
            );
            Route::get(
                'initial-payment/index',
                'Finance\InitialPaymentsController@index',
            )->name('initial-payment.index');
            Route::get(
                'mileage/index',
                'Finance\MileagesController@index',
            )->name('mileage.index');
        });
    Route::get('api-test', [CSVUploadController::class, 'apiTest'])->name(
        'api-test',
    );
});
