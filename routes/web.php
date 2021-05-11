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
Auth::routes();

/* DashboardController routes */
Route::get('/', 'DashboardController@index')->name('dashboard.base');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/notifications', 'DashboardController@showNotifications')->name('notifications');
Route::get('/notifications/delete', 'DashboardController@executeDeleteNotifications')->name('notifications.delete');

/* OrdersController routes */
Route::get('/create-order', 'OrdersController@create')->name('create_order');
Route::post('/create-exclude', 'OrdersController@executeAddExcludeField')->name('exclude_field');
Route::get('/create-vehicle', 'VehicleController@create')->name('create_vehicle');
Route::get('/order-bank', 'OrderController@showOrderBank')->name('order_bank');
Route::get('/completed-orders', 'OrderController@completedOrders')->name('completed_orders');
Route::get('/pipeline', 'VehicleController@showLedenStock')->name('pipeline');
Route::post('/pipeline/delete-selected', 'VehicleController@deleteSelected')->name('pipeline_delete');
Route::get('/ford-pipeline', 'VehicleController@showFordPipeline')->name('pipeline.ford');

Route::get('/manage-deliveries', 'OrderController@showManageDeliveries')->name('manage_deliveries');
Route::get('/orders/view/{order}', 'OrderController@show')->name('order.show');
Route::get('/vehicle/view/{vehicle}', 'VehicleController@show')->name('vehicle.show');
Route::get('/vehicle/edit/{vehicle}', 'VehicleController@edit')->name('edit_vehicle');
Route::get('/orders/edit/{order}', 'OrdersController@showEditOrder')->name('order.edit');
Route::post('/orders/edit/{order}', 'OrdersController@executeEditOrder')->name('order.update');
Route::get('/orders/reserve/{order}', 'OrdersController@showReserveOrder')->name('order.reserve');
Route::post('/orders/reserve/{order}', 'OrdersController@executeReserveOrder')->name('order.reserve.add');
Route::get('/orders/accept-date/{order}', 'OrderController@dateAccept')->name('order.date.accept');
Route::get('/orders/change-date/{order}', 'OrderController@showDateChange')->name('order.date.change');
Route::post('/orders/change-date/{order}', 'OrderController@storeDateChange')->name('order.date.update');
Route::get('/orders/delete/{type}/{order}', 'OrdersController@executeDeleteOrder')->name('order.delete');
route::get('/orders/pdf/{order}', 'OrdersController@executePDF')->name('order.pdf');
route::get('/orders/attachments-delete/{order_upload}', 'OrdersController@deleteOrderUpload')->name('order.attachment.delete');

/* ReportingController routes */
Route::get('/reporting', 'ReportingController@showReporting')->name('reporting');
Route::get('/reporting/download', 'ReportingController@executeReportDownload')->name('reporting_download');
Route::get('/custom-reports', 'ReportingController@showCustomReports')->name('custom_reports');

/* CSVUploadController routes */
Route::get('/csv-upload', 'CSVUploadController@showCsvUpload')->name('csv_upload');
Route::post('/csv-upload', 'CSVUploadController@executeCsvUpload')->name('csv_upload.import');

/* MessagesController routes */
Route::get('/messages', 'MessagesController@showMessages')->name('messages');
Route::get('/messages/new', 'MessagesController@showNewMessage')->name('message.new');
Route::post('/messages/new', 'MessagesController@executeNewMessage')->name('message.send');
Route::get('/messages/{message_group_id}', 'MessagesController@showMessage')->name('message.view');
Route::post('/messages/{message_group_id}', 'MessagesController@executeReplyMessage')->name('message.reply');

/* ProfileController routes */
Route::get('/profile', 'ProfileController@showProfile')->name('profile');
Route::post('/profile', 'ProfileController@executeUpdateProfile')->name('profile.update');
Route::get('/user-management', 'ProfileController@showUserManager')->name('user_manager')->middleware('can:admin');
Route::get('/user-management/add', 'ProfileController@showAddUser')->name('user.add');
Route::post('/user-management/add', 'ProfileController@executeAddUser')->name('user.create');
Route::get('/user-management/edit/{user}', 'ProfileController@showEditUser')->name('user.edit')->middleware('can:admin');
Route::post('/user-management/edit/{user}', 'ProfileController@storeEditUser')->name('user.update');
Route::get('/user-management/disable/{user}', 'ProfileController@toggleDisabled')->name('toggle.user.disabled')->middleware('can:admin');
Route::get('/user-management/delete/{user}', 'ProfileController@delete')->name('user.delete')->middleware('can:admin');

Route::get('/companies', 'CompanyController@index')->name('company_manager')->middleware('can:admin');
Route::get('/companies/add', 'CompanyController@create')->name('company.add')->middleware('can:admin');
Route::post('/companies/add', 'CompanyController@store')->name('company.create');
Route::get('/companies/edit/{company}', 'CompanyController@edit')->name('company.edit')->middleware('can:admin');
Route::post('/companies/edit/{company}', 'CompanyController@update')->name('company.update');


/* Vehicle Meta CRUD Routes
 * Added 04.05.2021 - By Link
*/

// Vehicle\Body
Route::get('/manage/vehiclemeta/body', 'Vehicle\BodyController@index')->name('meta.body.index');
// Vehicle\Colour
Route::get('/manage/vehiclemeta/colour', 'Vehicle\ColourController@index')->name('meta.colour.index');
// Vehicle\Derivative
Route::get('/manage/vehiclemeta/derivative', 'Vehicle\DerivativeController@index')->name('meta.derivative.index');
// Vehicle\Engine
Route::get('/manage/vehiclemeta/engine', 'Vehicle\EngineController@index')->name('meta.engine.index');
// Vehicle\Fuel
Route::get('/manage/vehiclemeta/fuel', 'Vehicle\FuelController@index')->name('meta.fuel.index');
// Vehicle\Transmission
Route::get('/manage/vehiclemeta/transmission', 'Vehicle\TransmissionController@index')->name('meta.transmission.index');
// Vehicle\Trim
Route::get('/manage/vehiclemeta/trim', 'Vehicle\TrimController@index')->name('meta.trim.index');
// Vehicle\Type
Route::get('/manage/vehiclemeta/type', 'Vehicle\TypeController@index')->name('meta.type.index');

/*
 * Data Management Routes
 * Added by Link Digital
 *
 */

Route::get('/link/test/', 'VehicleController@getVehicleMeta')->name('test');
Route::get('/link/test2/', 'CustomerController@buildNewCustomer')->name('test2');
Route::get('/link/test3/', 'ManufacturerController@buildManufacturerTable')->name('test3');
