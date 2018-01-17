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


Route::get('/documentation', function () {
    //return phpinfo();
    return File::get('/home/thomas/www/api/Documentation/index.html');
});Route::get('/11f2b0fa2f7edc4425a0bc912110fc9f.txt', function () {
    //return phpinfo();
    return File::get('/home/thomas/www/11f2b0fa2f7edc4425a0bc912110fc9f.txt');
});

Route::get('/test', function () {
    Mail::raw('Sending emails with Mailgun and Laravel is easy!', function ($message) {
        $message->subject('Mailgun and Laravel are awesome!');
        $message->from('no-reply@mail.sipper.pro', 'Website Name');
        $message->to('thomasbourcy@live.com');
    });

});

Route::get('/', function () {
    return view('welcome');
});

/**
 * Authentification
 */
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    /**
     * Home page
     */
    Route::get('/home', 'Back_office\HomeController@index');

    /**
     * Authentification
     */
    Route::get('/password_change', 'Auth\ChangePasswordController@changeGet')->name('changePassword');
    Route::post('/password_change', 'Auth\ChangePasswordController@changePost')->name('changePassword');

    /**
     * Application Users Resources
     */
    Route::resource('applicationUser', 'Back_office\ApplicationUsers\ApplicationUserController');

    Route::get('/applicationUser/orders_list/{id}', 'Back_office\ApplicationUsers\ApplicationUserOrdersController@index')->name('applicationUser.orders_list');

    Route::resource('cards', 'Back_office\ApplicationUsers\ApplicationUserPaymentMethodsController', ['except' => ['index', 'show', 'destroy']]);
    Route::get('cards/{applicationUser_id}', 'Back_office\ApplicationUsers\ApplicationUserPaymentMethodsController@index')->name('cards.index');
    Route::get('cards/show/{cardId}/{applicationUser_id}', 'Back_office\ApplicationUsers\ApplicationUserPaymentMethodsController@show')->name('cards.show');
    Route::delete('cards/delete/{cardId}/{applicationUser_id}', 'Back_office\ApplicationUsers\ApplicationUserPaymentMethodsController@destroy')->name('cards.destroy');

    Route::get('applicationUser/mangoPay_user_details/{applicationUser_id}', 'Back_office\ApplicationUsers\ApplicationUserController@showMangoPayUserDetails')->name('mangoPay.application_user.details');

    /**
     * Incidents
     */

    Route::resource('applicationUser_incident', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController', ['except' => ['index', 'create', 'store', 'update']]);
    Route::get('applicationUser_incident_create/{order_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@create')->name('applicationUser_incident.create');
    Route::post('applicationUser_incident_store/', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@store')->name('applicationUser_incident.store');
    Route::post('applicationUser_incident_update/', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@update')->name('applicationUser_incident.update');

    Route::get('applicationUser_incidents/index/{applicationUser_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@index')->name('applicationUser_incidents.index');

    Route::post('applicationUser_incident_memo', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@newMemo')->name('applicationUser_incident_memo.create');
    Route::get('applicationUser_incident_handled/{incident_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@incidentHandled')->name('applicationUser_incident_handled.handled');
    Route::get('applicationUser_incident_opened/{incident_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@incidentOpened')->name('applicationUser_incident_handled.opened');
    Route::get('applicationUser_incident_urgent/{incident_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@incidentUrgent')->name('applicationUser_incident_handled.urgent');

    Route::get('applicationUser_incident_refund_show/{order_id}', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@refundShow')->name('applicationUser_incident_refund.show');
    Route::post('applicationUser_incident_refund_refund', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@refund')->name('applicationUser_incident_refund.refund');
    Route::post('applicationUser_incident_refund_refund_share_bill', 'Back_office\ApplicationUsers\ApplicationUserIncidentsController@refundSharedBill')->name('applicationUser_incident_refund.refund_share_bill');

    /**
     * End incidents
     */

    /**
     * Partner
     */
    Route::resource('partner', 'Back_office\Partners\PartnerController');
    Route::resource('openings', 'Back_office\Partners\OpeningsController', ['only' => ['edit', 'update']]);
    Route::resource('menus', 'Back_office\Partners\MenuController', ['only' => ['edit']]);
    Route::resource('items', 'Back_office\Partners\ItemController', ['except' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]);
    Route::get('partner/{partner}/item', 'Back_office\Partners\ItemController@create')->name('item.create');
    Route::post('partner/{partner}/item', 'Back_office\Partners\ItemController@store')->name('item.store');
    Route::get('partner/{partner}/item/{id}', 'Back_office\Partners\ItemController@edit')->name('item.edit');
    Route::put('partner/{partner}/item/{id}', 'Back_office\Partners\ItemController@update')->name('item.update');
    Route::delete('partner/{partner}/item/{id}', 'Back_office\Partners\ItemController@destroy')->name('item.destroy');

    Route::get('partner/{partner}/bank_account_index', 'Back_office\Partners\BankAccountController@index')->name('bank_account.index');
    Route::get('partner/{partner}/bank_account_create', 'Back_office\Partners\BankAccountController@create')->name('bank_account.create');
    Route::post('partner/{partner}/bank_account_store', 'Back_office\Partners\BankAccountController@store')->name('bank_account.store');
    Route::get('partner/{partner}/bank_account_show/{bankAccount_id}', 'Back_office\Partners\BankAccountController@show')->name('bank_account.show');
    Route::get('partner/{partner}/bank_account_destroy/{bankAccount_id}', 'Back_office\Partners\BankAccountController@destroy')->name('bank_account.destroy');
    Route::get('partner/{partner}/bank_account_setUsed/{bankAccount_id}', 'Back_office\Partners\BankAccountController@setBankAccountAsUsed')->name('bank_account.setUsed');

    Route::get('partner/{partner_id}/wallet', 'Back_office\Partners\WalletController@show')->name('wallet.show');
    Route::get('partner/{partner_id}/wallet_payOut', 'Back_office\Partners\WalletController@walletPayOut')->name('wallet.payOut');

    Route::get('partner/{partner_id}/kyc_index', 'Back_office\Partners\KYCController@index')->name('kyc.index');
    Route::get('partner/{partner_id}/kyc_create', 'Back_office\Partners\KYCController@create')->name('kyc.create');
    Route::get('partner/{partner_id}/kyc_show/{kycDoc_id}', 'Back_office\Partners\KYCController@show')->name('kyc.show');

    Route::get('kyc_shareholder_declaration', 'Back_office\Partners\KYCController@downloadShareholderDeclaration')->name('kyc.downloadShareholderDeclaration');

    Route::get('partner/mangoPay_user_details/{partner_id}', 'Back_office\Partners\PartnerController@showMangoPayUserDetails')->name('mangoPay.partner.details');

    Route::get('partner/invoiceGenerator/{partner_id}', 'Back_office\Partners\InvoiceController@invoiceGenerate');
    Route::get('partner/index/{partner_id}', 'Back_office\Partners\InvoiceController@index')->name('partner.invoices.index');
    Route::get('partner/invoiceGenerateLastMonth/{partner_id}', 'Back_office\Partners\InvoiceController@invoiceGenerateLastMonth')->name('partner.invoices.generateLastMonth');
    Route::get('partner/invoiceDownload/{invoice_id}', 'Back_office\Partners\InvoiceController@download')->name('partner.invoices.download');
    /**
     * Orders resources
     */
    Route::resource('/order', 'Back_office\Orders\OrderController');

    /**
     * Exports
     */
    Route::get('/export', 'Back_office\Activities\Export\ExcelController@index')->name('export.index');
    Route::get('/export/menus', 'Back_office\Activities\Export\ExcelController@orders')->name('export.orders');
    Route::get('/export/orders', 'Back_office\Activities\Export\ExcelController@menus')->name('export.menus');
    Route::get('/export/applicationUsers', 'Back_office\Activities\Export\ExcelController@applicationUsers')->name('export.applicationUsers');
    Route::get('/export/partners', 'Back_office\Activities\Export\ExcelController@partners')->name('export.partners');

    /**
     * Figures / charts
     */
    Route::get('/figures', 'Back_office\Activities\Charts\ChartsController@home')->name('charts.home');

    /**
     * Notifications
     */
    Route::get('/notification/form', 'Back_office\Notifications\NotificationController@notificationForm')->name('notification.form');
    Route::post('/notification/send', 'Back_office\Notifications\NotificationController@notificationSend')->name('notification.send');

    Route::get('/notification/targeted_group/form', 'Back_office\Notifications\NotificationController@targetedGroupNotificationForm')->name('targeted_group.notification.form');
    Route::get('/notification/targeted_user/form', 'Back_office\Notifications\NotificationController@targetedUserNotificationForm')->name('targeted_user.notification.form');
    Route::post('/notification/targeted_group/send', 'Back_office\Notifications\NotificationController@targetedGroupNotificationSend')->name('targeted_group.notification.send');
    Route::post('/notification/targeted_user/send', 'Back_office\Notifications\NotificationController@targetedUserNotificationSend')->name('targeted_user.notification.send');
    /**
     * Leads
     */
    Route::get('/leads', 'Back_office\Others\GetLeadController@index')->name('leads.index');

    /**
     * MangoPay
     */

    Route::get('mangoPay_events_index', 'Back_office\MangoPay\EventsController@index')->name('mangoPay.events.index');

    Route::get('mangoPay_hooks_index', 'Back_office\MangoPay\HooksController@index')->name('mangoPay.hooks.index');
    Route::get('mangoPay_hooks_create', 'Back_office\MangoPay\HooksController@create')->name('mangoPay.hooks.create');
    Route::post('mangoPay_hooks_store', 'Back_office\MangoPay\HooksController@store')->name('mangoPay.hooks.store');
    Route::get('mangoPay_hooks_disable/{hook_id}', 'Back_office\MangoPay\HooksController@disable')->name('mangoPay.hooks.disable');
    Route::get('mangoPay_hooks_enable/{hook_id}', 'Back_office\MangoPay\HooksController@enable')->name('mangoPay.hooks.enable');

    Route::get('mangoPay_disputes_index/', 'Back_office\MangoPay\DisputesController@index')->name('mangoPay.disputes.index');
    Route::get('mangoPay_disputes_show/{dispute_id}', 'Back_office\MangoPay\DisputesController@show')->name('mangoPay.disputes.show');
    Route::get('mangoPay_disputes_submit/{dispute_id}', 'Back_office\MangoPay\DisputesController@submit')->name('mangoPay.disputes.submit');

    /**
     * Communication
     */

    Route::get('list_of_feeds', 'Back_office\Communication\NewsController@index')->name('rss_feeds');
    Route::get('fb_post', 'Back_office\Communication\NewsController@onGoToFB')->name('fb_post');
});

Route::get('mangoPay_hook_error', 'Back_office\MangoPay\HooksController@triggerErrorEvent');

