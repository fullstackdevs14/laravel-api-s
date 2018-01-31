<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//
//
//
//Tools
//
//
//

Route::get('/toolGetOpenings', 'Tools\OpeningsController@toolGetOpenings');
Route::get('/toolGetActiveCategories', 'Tools\CategoriesController@toolGetActiveCategories');
Route::get('/toolGetTaxes', 'Tools\TaxesController@toolGetTaxes');
Route::get('/toolGetCountries', 'Tools\CountriesController@toolGetCountries');

//
//
//
//MangoPay
//
//
//
Route::group(['middleware' => ['jwt.applicationUser']], function () {

    Route::post('/RegisterNewCard', 'API\ApplicationUsers\MangoPayController@registerNewCard');
    Route::get('/MangoPayGetCards', 'API\ApplicationUsers\MangoPayController@getActiveCards');
    Route::post('/MangoDeactivateCard', 'API\ApplicationUsers\MangoPayController@deactivateCard');
    Route::post('/MangoSetCardAsUsed', 'API\ApplicationUsers\MangoPayController@setCardAsUsed');

});

Route::post('/MangoPayCardReg', 'API\ApplicationUsers\MangoPayController@cardRegistration');

//
//
//
//ApplicationUser
//
//
//

Route::post('/applicationUserRegister', 'API\ApplicationUsers\ApplicationUserController@register');
Route::post('/applicationUserSignIn', 'API\ApplicationUsers\ApplicationUserController@login');

Route::get('/emailConfirmationToken/{token}', 'API\ApplicationUsers\EmailActivationController@confirmEmail');
Route::post('/resetPasswordWithEmail', 'API\ApplicationUsers\ResetPasswordController@resetPasswordWithEmail');
Route::group(['middleware' => ['web']], function () {
    Route::get('/ApplicationUserResetPasswordForm/{token}', 'API\ApplicationUsers\ResetPasswordController@applicationUserResetPasswordForm');
    Route::post('ApplicationUserResetPasswordRequest', 'API\ApplicationUsers\ResetPasswordController@applicationUserResetPasswordRequest')->name('applicationUser.renewPassword');
});

Route::group(['middleware' => ['jwt.applicationUser']], function () {

    Route::get('/test', 'API\ApplicationUsers\ApplicationUserController@testAuth');
    Route::post('/auth', 'API\ApplicationUsers\ApplicationUserController@auth');
    Route::get('/getApplicationUser', 'API\ApplicationUsers\ApplicationUserController@GetApplicationUser');

    Route::get('/applicationUserLogout', 'API\ApplicationUsers\ApplicationUserController@logout');
    Route::post('/applicationUserUpdate', 'API\ApplicationUsers\ApplicationUserController@update');

    Route::post('/applicationUserNewEmail', 'API\ApplicationUsers\EmailReplaceController@newEmailRequest');

    Route::get('/getPartners', 'API\ApplicationUsers\ApplicationUserPartnersController@getPartners');
    Route::post('/getPartner', 'API\ApplicationUsers\ApplicationUserPartnersController@getPartner');
    Route::post('/searchPartners', 'API\ApplicationUsers\ApplicationUserPartnersController@searchPartners');
    Route::post('/getSortMenu', 'API\ApplicationUsers\ApplicationUserPartnersController@getSortMenu');
    Route::post('/getSchedule', 'API\ApplicationUsers\ApplicationUserPartnersController@getSchedule');

    Route::get('/getOrders', 'API\Orders\ApplicationUserOrderController@getOrders');
    Route::post('/order', 'API\Orders\ApplicationUserOrderController@order');
    Route::post('/orderShareBill', 'API\Orders\ApplicationUserOrderController@orderShareBill');
    Route::post('/getSharedOrder', 'API\Orders\ApplicationUserOrderController@getSharedOrder');
    Route::post('/acceptSharedOrder', 'API\Orders\ApplicationUserOrderController@acceptSharedOrder');
    Route::post('/refuseSharedOrder', 'API\Orders\ApplicationUserOrderController@refuseSharedOrder');

    Route::post('/helpMessage', 'API\ApplicationUsers\MessagesController@helpMessage');
    Route::post('/issueMessage', 'API\ApplicationUsers\MessagesController@issueMessage');

    Route::post('/registerNotifications', 'API\ApplicationUsers\NotificationController@register');

    Route::post('/getInvoicesForOrder', 'API\ApplicationUsers\InvoiceController@sendInvoicesByMail');
});

Route::get('/applicationUserNewEmailConfirmation/{token}', 'API\ApplicationUsers\EmailReplaceController@newEmailConfirmation');

//
//
//
//Partner
//
//
//

Route::post('/applicationPartnerSignIn', 'API\Partners\PartnerController@login');

Route::group(['middleware' => ['jwt.partner']], function () {

    Route::get('/PartnerLogout', 'API\Partners\PartnerController@logout');

    Route::get('/applicationPartnerGetOrders', 'API\Orders\PartnerOrdersController@getOrders');
    Route::get('/applicationPartnerGetOrdersRecords', 'API\Orders\PartnerOrdersController@getOldOrders');
    Route::post('applicationPartnerSearchOrderRecord', 'API\Orders\PartnerOrdersController@searchOrder');
    Route::post('/applicationPartnerAcceptOrder', 'API\Orders\PartnerOrdersController@acceptOrder');
    Route::post('/applicationPartnerDeliverOrder', 'API\Orders\PartnerOrdersController@deliverOrder');
    Route::post('/applicationPartnerDeclineOrder', 'API\Orders\PartnerOrdersController@declineOrder');

    Route::get('/applicationPartnerGetPartner', 'API\Partners\PartnerController@getPartner');
    Route::get('/applicationPartnerUpdateOpenStatus', 'API\Partners\PartnerController@updateOpenStatus');
    Route::get('/applicationPartnerUpdateHHStatus', 'API\Partners\PartnerController@updateHHStatus');
    Route::put('/applicationPartnerUpdate', 'API\Partners\PartnerController@update');

    Route::post('/applicationPartnerModificationMessage', 'API\Partners\PartnerController@modificationMessage');

    Route::get('/applicationPartnerGetOpenings', 'API\Partners\PartnerOpeningsController@getOpenings');
    Route::put('/applicationPartnerModifyOpenings', 'API\Partners\PartnerOpeningsController@modifyOpenings');

    Route::get('/applicationPartnerGetMenu', 'API\Partners\PartnerMenuController@getMenu');
    Route::put('/applicationPartnerModifyItem', 'API\Partners\PartnerMenuController@modifyItem');
    Route::delete('/applicationPartnerDeleteItem/{itemId}', 'API\Partners\PartnerMenuController@deleteItem');
    Route::post('/applicationPartnerCreateItem', 'API\Partners\PartnerMenuController@createItem');

    /**
     * CHARTS
     */
    Route::get('/applicationPartnerFillCharts', 'API\Partners\ChartsController@fillCharts');

});
