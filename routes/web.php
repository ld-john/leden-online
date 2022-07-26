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

use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;

Auth::routes();

/* Dashboard Controller routes */

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
        Route::prefix('vehicle')
            ->name('vehicle.')
            ->group(function () {
                Route::get('/delete/{vehicle}', 'destroy')->name('delete');
                Route::get('/view/{vehicle}', 'show')->name('show');
                Route::get('/edit/{vehicle}', 'edit')->name('edit');
                Route::get('/recycle-bin', 'recycle')->name('recycle_bin');
                Route::get('/force-delete/{vehicle}', 'forceDelete')->name(
                    'force-delete',
                );
                Route::get('/restore/{vehicle}', 'restore')->name('restore');
            });
    });
    Route::get('/vehicle/foexport/', [
        VehicleController::class,
        'factory_order_export',
    ])->name('factory_order.export');
    Route::get('/vehicle/eurovhcexport/', [
        VehicleController::class,
        'europe_vhc_export',
    ])->name('europe_vhc_export.export');
    Route::get('/vehicle/ukvhcexport/', [
        VehicleController::class,
        'uk_vhc_export',
    ])->name('uk_vhc_export.export');
    Route::get('/vehicle/instockexport/', [
        VehicleController::class,
        'in_stock_export',
    ])->name('in_stock_export.export');
    Route::get('/vehicle/readyfordeliveryexport/', [
        VehicleController::class,
        'ready_for_delivery_export',
    ])->name('readyfordeliveryexport.export');
    Route::get('/vehicle/deliverybooked/', [
        VehicleController::class,
        'delivery_booked_export',
    ])->name('deliverybooked.export');
    Route::get('/vehicle/awaitingship/', [
        VehicleController::class,
        'awaiting_ship_export',
    ])->name('awaitingship.export');
    Route::get('/vehicle/atconverter/', [
        VehicleController::class,
        'at_converter_export',
    ])->name('atconverter.export');
    Route::get('/vehicle/registered/', [
        VehicleController::class,
        'in_stock_registered_export',
    ])->name('registered.export');

    /* ReportingController routes */
    Route::prefix('reporting')
        ->controller('ReportingController')
        ->group(function () {
            Route::get('/', 'showReporting')->name('reporting');
            Route::get('/monthly-{report}/{year}/{month}', 'monthlyDownload');
            Route::get(
                '/quarterly-{report}/{year}/{quarter}',
                'quarterlyDownload',
            );
            Route::get('/weekly-{report}/{year}/{quarter}', 'weeklyDownload');
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

    //    Route::get('/link/test/4', 'OrderController@dataTest')->name('test4');
    //    Route::get('/link/test/', 'VehicleController@getVehicleMeta')->name('test');
    //    Route::get('/link/test2/', 'CustomerController@buildNewCustomer')->name('test2');
    //    Route::get('/link/test3/', 'ManufacturerController@buildManufacturerTable')->name('test3');
    //    Route::get('/link/completed-date/', 'VehicleController@completedDateCleanup')->name('test4');
    //    Route::get('/link/order-ref/', 'VehicleController@orderRefCleanup')->name('test5');
    //    Route::get('/link/date-clean-up', 'VehicleController@date_cleaner')->name('test6');
    //    Route::get('/link/customer-name-clean-up', 'CustomerController@name_cleaner')->name('test7');
    //    Route::get('/link/vehicle-broker-dealer-clean-up', 'OrderController@VehicleBrokerDealerCleanup')->name('test8');
    //    Route::get('/link/invoice-value-clean-up', 'OrderController@invoice_value_cleaner')->name('test9');
    //    Route::get('/link/fit-option-clean-up', 'VehicleController@fitOptionsCleanUp');
    Route::get(
        '/link/comment-clean-up',
        'CommentController@makeCommentsPolymorphic',
    );
    Route::get(
        '/link/vehicle-model-clean-up',
        'ManufacturerController@vehicle_model_clean_up',
    );
    Route::get(
        '/link/fit-options-clean-up',
        'FitOptionsController@fitOptionsClean',
    );
    Route::get('/link/meta-clean-up', 'VehicleMetaController@clean');
    Route::get('/link/meta-add-on', 'VehicleMetaController@addon');
});
