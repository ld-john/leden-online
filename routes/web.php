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

use App\Http\Controllers\OrderController;
use App\Http\Controllers\VehicleController;

Auth::routes();

/* Dashboard Controller routes */

Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard.base');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    /* Notifications Routes */

    Route::get('/notifications', 'DashboardController@showNotifications')->name(
        'notifications',
    );
    Route::get(
        '/notifications/delete',
        'DashboardController@executeDeleteNotifications',
    )->name('notifications.delete');
    Route::get(
        '/notifications/readAll',
        'DashboardController@readAllNotifications',
    )->name('notifications.read');
    Route::get(
        '/notifications/mark-read/{notification}',
        'DashboardController@readNotifications',
    )->name('notifications.mark-read');
    Route::get(
        '/notifications/mark-unread/{notification}',
        'DashboardController@unreadNotifications',
    )->name('notifications.mark-unread');

    /* Order Controller Routes */
    Route::get('/create-order', 'OrderController@create')->name('create_order');
    Route::get('/order-bank', 'OrderController@showOrderBank')->name(
        'order_bank',
    );
    Route::get('/completed-orders', 'OrderController@completedOrders')->name(
        'completed_orders',
    );
    Route::get(
        '/manage-deliveries',
        'OrderController@showManageDeliveries',
    )->name('manage_deliveries');

    Route::prefix('orders')
        ->controller(OrderController::class)
        ->group(function () {
            Route::get('/view/{order}', 'show')->name('order.show');
            Route::get('/duplicate/{order}', 'duplicate')->name(
                'order.duplicate',
            );
            Route::get('/edit/{order}', 'edit')->name('order.edit');
            Route::get('/{vehicle}', 'showReserveOrder')->name('order.reserve');
            Route::get('/accept-date/{order}', 'dateAccept')->name(
                'order.date.accept',
            );
            Route::get('/change-date/{order}', 'showDateChange')->name(
                'order.date.change',
            );
            Route::post('/change-date/{order}', 'storeDateChange')->name(
                'order.date.update',
            );
            Route::get('/pdf/{order}', 'downloadPDF')->name('order.pdf');
            Route::get('/delete/{order}', 'destroy')->name('order.destroy');
        });

    /* Reservation Controller Routes */
    Route::get(
        '/reserve-vehicle/{vehicle}',
        'ReservationController@create',
    )->name('reservation.create');
    Route::get('/reservations', 'ReservationController@index')->name(
        'reservation.index',
    );
    Route::get(
        '/reservations/{reservation}/extend',
        'ReservationController@extend',
    )->name('reservation.extend');
    Route::get(
        '/reservations/{user}/toggle',
        'ReservationController@toggle',
    )->name('reservation.toggle');
    Route::get(
        '/reservations/{vehicle}/admin',
        'ReservationController@admin_create',
    )->name('reservation.admin');

    /* Vehicle Controller Routes */
    Route::get('/create-vehicle', 'VehicleController@create')->name(
        'create_vehicle',
    );
    Route::get('/pipeline', 'VehicleController@showLedenStock')->name(
        'pipeline',
    );
    Route::get(
        '/ringfenced-stock',
        'VehicleController@showRingFencedStock',
    )->name('ring_fenced_stock');
    Route::post(
        '/pipeline/delete-selected',
        'VehicleController@deleteSelected',
    )->name('pipeline_delete');
    Route::get('/vehicle/delete/{vehicle}', 'VehicleController@destroy')->name(
        'vehicle.delete',
    );
    Route::get('/ford-pipeline', 'VehicleController@showFordPipeline')->name(
        'pipeline.ford',
    );
    Route::get('/vehicle/view/{vehicle}', 'VehicleController@show')->name(
        'vehicle.show',
    );
    Route::get('/vehicle/edit/{vehicle}', 'VehicleController@edit')->name(
        'edit_vehicle',
    );
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
    Route::get('/vehicle/recycle-bin', 'VehicleController@recycle')->name(
        'vehicle.recycle_bin',
    );
    Route::get(
        '/vehicle/force-delete/{vehicle}',
        'VehicleController@forceDelete',
    )->name('vehicle.force-delete');
    Route::get('/vehicle/restore/{vehicle}', 'VehicleController@restore')->name(
        'vehicle.restore',
    );

    /* ReportingController routes */
    Route::get('/reporting', 'ReportingController@showReporting')->name(
        'reporting',
    );
    Route::get(
        '/reporting/monthly-{report}/{year}/{month}',
        'ReportingController@monthlyDownload',
    );
    Route::get(
        '/reporting/quarterly-{report}/{year}/{quarter}',
        'ReportingController@quarterlyDownload',
    );
    Route::get(
        '/reporting/weekly-{report}/{year}/{quarter}',
        'ReportingController@weeklyDownload',
    );

    /* CSVUploadController routes */
    Route::get('/csv-upload', 'CSVUploadController@showCsvUpload')->name(
        'csv_upload',
    );
    Route::get('/rf-upload', 'CSVUploadController@showRingFenceUpload')->name(
        'rf_upload',
    );
    Route::get(
        '/fit-option-upload',
        'CSVUploadController@showFitOptionUpload',
    )->name('fit_option_upload');
    Route::post('/csv-upload', 'CSVUploadController@executeCsvUpload')->name(
        'csv_upload.import',
    );
    Route::post('/rf-upload', 'CSVUploadController@executeRfUpload')->name(
        'rf_upload.import',
    );
    Route::post(
        '/import_parse',
        'CSVUploadController@parseFitOptionImport',
    )->name('import_parse');
    Route::post(
        '/import_process',
        'CSVUploadController@processFitOptionImport',
    )->name('import_process');

    /* Customer Controller Routes */
    Route::get('/customers', 'CustomerController@index')->name(
        'customer.index',
    );

    /* MessagesController routes */
    Route::get('/messages', 'MessagesController@showMessages')->name(
        'messages',
    );
    Route::get('/messages/new', 'MessagesController@showNewMessage')->name(
        'message.new',
    );
    Route::post('/messages/new', 'MessagesController@executeNewMessage')->name(
        'message.send',
    );
    Route::get(
        '/messages/{message_group_id}',
        'MessagesController@showMessage',
    )->name('message.view');
    Route::post(
        '/messages/{message_group_id}',
        'MessagesController@executeReplyMessage',
    )->name('message.reply');

    /* ProfileController routes */
    Route::get('/profile', 'ProfileController@showProfile')->name('profile');
    Route::post('/profile', 'ProfileController@executeUpdateProfile')->name(
        'profile.update',
    );
    Route::get('/user-management', 'ProfileController@showUserManager')
        ->name('user_manager')
        ->middleware('can:admin');
    Route::get('/user-management/add', 'ProfileController@showAddUser')->name(
        'user.add',
    );
    Route::post(
        '/user-management/add',
        'ProfileController@executeAddUser',
    )->name('user.create');
    Route::get('/user-management/edit/{user}', 'ProfileController@showEditUser')
        ->name('user.edit')
        ->middleware('can:admin');
    Route::post(
        '/user-management/edit/{user}',
        'ProfileController@storeEditUser',
    )->name('user.update');
    Route::get(
        '/user-management/disable/{user}',
        'ProfileController@toggleDisabled',
    )
        ->name('toggle.user.disabled')
        ->middleware('can:admin');
    Route::get('/user-management/delete/{user}', 'ProfileController@delete')
        ->name('user.delete')
        ->middleware('can:admin');

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

    Route::prefix('manage')
        ->name('meta.')
        ->group(function () {
            // Vehicle\Colour
            Route::get(
                '/vehiclemeta/colour',
                'Vehicle\ColourController@index',
            )->name('colour.index');
            // Vehicle\Derivative
            Route::get(
                '/vehiclemeta/derivative',
                'Vehicle\DerivativeController@index',
            )->name('derivative.index');
            // Vehicle\Engine
            Route::get(
                '/vehiclemeta/engine',
                'Vehicle\EngineController@index',
            )->name('engine.index');
            // Vehicle\Fuel
            Route::get(
                '/vehiclemeta/fuel',
                'Vehicle\FuelController@index',
            )->name('fuel.index');
            // Vehicle\Transmission
            Route::get(
                '/vehiclemeta/transmission',
                'Vehicle\TransmissionController@index',
            )->name('transmission.index');
            // Vehicle\Trim
            Route::get(
                '/vehiclemeta/trim',
                'Vehicle\TrimController@index',
            )->name('trim.index');
            // Vehicle\Type
            Route::get(
                '/vehiclemeta/type',
                'Vehicle\TypeController@index',
            )->name('type.index');
            // Factory Fit Options
            Route::get(
                '/vehiclemeta/factoryfit',
                'FitOptionsController@factoryFitIndex',
            )->name('factoryfit.index');
            // Dealer Fit Options
            Route::get(
                '/vehiclemeta/dealerfit',
                'FitOptionsController@dealerFitIndex',
            )->name('dealerfit.index');
            Route::get(
                '/vehiclemeta/make',
                'Vehicle\MakeController@index',
            )->name('make.index');
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
});
