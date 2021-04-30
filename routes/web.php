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
Route::post('/create-order', 'OrdersController@executeAddOrder')->name('add_order');
Route::post('/create-exclude', 'OrdersController@executeAddExcludeField')->name('exclude_field');
Route::get('/completed-orders', 'OrdersController@showCompletedOrders')->name('completed_orders');
Route::get('/pipeline', 'OrdersController@showPipeline')->name('pipeline');
Route::post('/pipeline/delete-selected', 'OrdersController@executeDeleteSelected')->name('pipeline_delete');
Route::get('/ford-pipeline', 'OrdersController@showFordPipeline')->name('pipeline.ford');
Route::get('/order-bank', 'OrdersController@showOrderBank')->name('order_bank');
Route::get('/manage-deliveries', 'OrdersController@showManageDeliveries')->name('manage_deliveries');
Route::get('/orders/view/{order}', 'OrdersController@showOrder')->name('order.show');
Route::get('/orders/edit/{order}', 'OrdersController@showEditOrder')->name('order.edit');
Route::post('/orders/edit/{order}', 'OrdersController@executeEditOrder')->name('order.update');
Route::get('/orders/reserve/{order}', 'OrdersController@showReserveOrder')->name('order.reserve');
Route::post('/orders/reserve/{order}', 'OrdersController@executeReserveOrder')->name('order.reserve.add');
Route::get('/orders/accept-date/{order}', 'OrdersController@executeDateAccept')->name('order.date.accept');
Route::get('/orders/change-date/{order}', 'OrdersController@showDateChange')->name('order.date.change');
Route::post('/orders/change-date/{order}', 'OrdersController@executeDatechange')->name('order.date.update');
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
Route::get('/user-management', 'ProfileController@showUserManager')->name('user_manager');
Route::get('/user-management/add', 'ProfileController@showAddUser')->name('user.add');
Route::post('/user-management/add', 'ProfileController@executeAddUser')->name('user.create');
Route::get('/user-management/edit/{user_id}', 'ProfileController@showEditUser')->name('user.edit');
Route::post('/user-management/edit/{user_id}', 'ProfileController@executeEditUser')->name('user.update');
Route::get('/user-management/disable/{user_id}', 'ProfileController@toggleDisabled')->name('toggle.user.disabled');

Route::get('/companies', 'ProfileController@showCompanies')->name('company_manager');
Route::get('/companies/add', 'ProfileController@showAddCompany')->name('company.add');
Route::post('/companies/add', 'ProfileController@executeAddCompany')->name('company.create');
Route::get('/companies/edit/{company_id}', 'ProfileController@showEditCompany')->name('company.edit');
Route::post('/companies/edit/{company_id}', 'ProfileController@executeEditCompany')->name('company.update');

/*
 * Data Management Routes
 * Added by Link Digital
 *
 */

Route::get('/link/test/', 'VehicleController@buildNewVehicle')->name('test');
Route::get('/link/test2/', 'CustomerController@buildNewCustomer')->name('test2');
Route::get('/link/test3/', 'ManufacturerController@buildManufacturerTable')->name('test3');
