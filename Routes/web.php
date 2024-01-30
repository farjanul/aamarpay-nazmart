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
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/* frontend routes */
Route::prefix('aamarpaypaymentgateway')->group(function() {
    Route::post("landlord-price-plan-aamarpay",[\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayController::class,"landlordPricePlanIpn"])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name("aamarpaypaymentgateway.landlord.price.plan.ipn");

});


/* tenant payment ipn route*/
Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class
])->prefix('aamarpaypaymentgateway')->group(function () {
    Route::post("tenant-price-plan-aamarpay",[\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayController::class,"TenantSiteswayIpn"])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name("aamarpaypaymentgateway.tenant.price.plan.ipn");

});

/* admin panel routes landlord */
Route::group(['middleware' => ['auth:admin','adminglobalVariable', 'set_lang'],'prefix' => 'admin-home'],function () {
    Route::prefix('aamarpaypaymentgateway')->group(function() {
        Route::get('/settings', [\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayAdminPanelController::class,"settings"])
            ->name("aamarpaypaymentgateway.landlord.admin.settings");
        Route::post('/settings', [\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayAdminPanelController::class,"settingsUpdate"]);
    });
});


Route::group(['middleware' => [
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'tenant_status',
    'set_lang'
    ],'prefix' => 'admin-home'],function () {
    Route::prefix('aamarpaypaymentgateway/tenant')->group(function() {
        Route::get('/settings', [\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayAdminPanelController::class,"settings"])
            ->name("aamarpaypaymentgateway.tenant.admin.settings");
        Route::post('/settings', [\Modules\AamarPayPaymentGateway\Http\Controllers\AamarPayPaymentGatewayAdminPanelController::class,"settingsUpdate"]);
    });
});

