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

/*Auth::routes(); */

// Client Routes
Route::get('/', 'Client\HomeController@index')->name('home');
Route::get('/contact', 'Client\HelpController@contact')->name('contact');
Route::get('/faq', 'Client\HelpController@faq')->name('faq');
Route::get('/terms', 'Client\HelpController@terms')->name('terms');
Route::get('/privacy', 'Client\HelpController@privacy')->name('privacy');
Route::post('/contactmail', 'Client\HelpController@contactmail')->name('help.contactmail');

/* Admin Routes */
Route::get('/admin', 'Admin\Auth\LoginController@showLoginForm')->name('admin');
Route::post('/admin', 'Admin\Auth\LoginController@login')->name('auth.login');
Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
Route::get('/admin/dashboard/sendalert/{dtype}', 'Admin\DashboardController@sendalert')->name('admin.dashboard.sendalert');
Route::get('/admin/statistics', 'Admin\DashboardController@chart')->name('admin.chart');
Route::get('/home', 'HomeController@index')->name('home');

//language
Route::get('admin/lang/{lang}', ['as' => 'lang.switch', 'uses' => 'Admin\LanguageController@switchLang']);

//for dynamic dashboard data
Route::get('/admin/getdashboarddatas', 'Admin\DashboardController@getdashboarddata');

//Admin profile
Route::get('/admin/profile', 'Admin\AdminController@profile')->name('admin.profile');
Route::post('/admin/profile', 'Admin\AdminController@profileupdate');
Route::get('/admin/resetpassword', 'Admin\AdminController@resetpass')->name('admin.resetpassword');
Route::post('/admin/resetpassword', 'Admin\AdminController@passupdate');

//accounts
Route::get('/admin/accounts', 'Admin\AccountsController@index')->name('accounts.index');
Route::get('/admin/accounts/pending', 'Admin\AccountsController@pending')->name('accounts.pending');
Route::get('/admin/accounts/online', 'Admin\AccountsController@online')->name('accounts.online');
Route::get('/admin/accounts/getcount', 'Admin\AccountsController@getcount');
Route::get('/admin/accounts/changestatus/{id}/{status}', 'Admin\AccountsController@changestatus');
Route::get('/admin/accounts/show/{id}', 'Admin\AccountsController@show')->name('accounts.show');
Route::post('/admin/accounts/search', 'Admin\AccountsController@search')->name('accounts.search');
Route::post('/admin/accounts/onlinesearch', 'Admin\AccountsController@onlinesearch')->name('accounts.onlinesearch');
Route::get('/admin/accounts/search', 'Admin\AccountsController@search')->name('accounts.search');
Route::get('/admin/accounts/onlinesearch', 'Admin\AccountsController@onlinesearch')->name('accounts.onlinesearch');
Route::get('/admin/accounts/sendalert/{id}', 'Admin\AccountsController@sendalert');
Route::get('/admin/accounts/follow/{type}/{id}', 'Admin\AccountsController@follow')->name('accounts.follow');

//settings
Route::get('/admin/mainsettings', 'Admin\SettingsController@mainsettings')->name('admin.mainsettings');
Route::post('/admin/mainsettings', 'Admin\SettingsController@mainsettingsupdate');
Route::get('/admin/socialsettings', 'Admin\SettingsController@socialsettings')->name('admin.socialsettings');
Route::post('/admin/socialsettings', 'Admin\SettingsController@socialsettingsupdate');
Route::get('/admin/purchasesettings', 'Admin\SettingsController@purchasesettings')->name('admin.purchasesettings');
Route::post('/admin/purchaseupdate', 'Admin\SettingsController@purchaseupdate');
Route::get('/admin/logosettings', 'Admin\SettingsController@logosettings')->name('admin.logosettings');
Route::post('/admin/logosettings', 'Admin\SettingsController@logosettingsupdate');
Route::get('/admin/primesettings', 'Admin\SettingsController@primesettings')->name('admin.primesettings');
Route::post('/admin/primesettings', 'Admin\SettingsController@primesettingsupdate');
Route::get('/admin/sliderlist', 'Admin\SettingsController@sliderlist')->name('admin.settings.sliderlist');
Route::get('/admin/slidercreate', 'Admin\SettingsController@slidercreate')->name('admin.settings.slidercreate');
Route::post('/admin/slidercreate', 'Admin\SettingsController@sliderstore');
Route::get('/admin/slideredit/{id}', 'Admin\SettingsController@slideredit')->name('admin.settings.slideredit');
Route::post('/admin/sliderupdate/{id}', 'Admin\SettingsController@sliderupdate')->name('admin.settings.sliderupdate');
Route::get('/admin/sliderdestroy/{id}', 'Admin\SettingsController@sliderdestroy');
Route::get('/admin/slidershow/{id}', 'Admin\SettingsController@slidershow')->name('admin.settings.slidershow');
Route::get('/admin/emailsettings', 'Admin\SettingsController@emailsettings')->name('admin.emailsettings');
Route::post('/admin/emailsettings', 'Admin\SettingsController@emailsettingsupdate');
Route::get('/admin/notificationsettings', 'Admin\SettingsController@notificationsettings')->name('admin.notificationsettings');
Route::post('/admin/notificationsettings', 'Admin\SettingsController@updatenotificationsettings');

//helps
Route::get('/admin/helps', 'Admin\HelpsController@index')->name('admin.helps.index');
Route::get('/admin/helps/create', 'Admin\HelpsController@create')->name('admin.helps.create');
Route::post('/admin/helps/create', 'Admin\HelpsController@store');
Route::get('/admin/helps/show/{id}', 'Admin\HelpsController@show')->name('admin.helps.show');
Route::get('/admin/helps/edit/{id}', 'Admin\HelpsController@edit')->name('admin.helps.edit');
Route::post('/admin/helps/update/{id}', 'Admin\HelpsController@update')->name('admin.helps.update');
Route::get('/admin/helps/destroy/{id}', 'Admin\HelpsController@destroy');
Route::get('/admin/helps/search', 'Admin\HelpsController@search')->name('helps.search');

//gems
Route::get('/admin/gems', 'Admin\GemsController@index')->name('admin.gems.index');
Route::get('/admin/gems/create', 'Admin\GemsController@create')->name('admin.gems.create');
Route::post('/admin/gems/create', 'Admin\GemsController@store');
Route::get('/admin/gems/show/{id}', 'Admin\GemsController@show')->name('admin.gems.show');
Route::get('/admin/gems/edit/{id}', 'Admin\GemsController@edit')->name('admin.gems.edit');
Route::post('/admin/gems/update/{id}', 'Admin\GemsController@update')->name('admin.gems.update');
Route::get('/admin/gems/destroy/{id}', 'Admin\GemsController@destroy');
Route::get('/admin/gems/search', 'Admin\GemsController@search')->name('gems.search');

//gifts
Route::get('/admin/gifts', 'Admin\GiftsController@index')->name('admin.gifts.index');
Route::get('/admin/gifts/create', 'Admin\GiftsController@create')->name('admin.gifts.create');
Route::post('/admin/gifts/create', 'Admin\GiftsController@store');
Route::get('/admin/gifts/edit/{id}', 'Admin\GiftsController@edit')->name('admin.gifts.edit');
Route::post('/admin/gifts/update/{id}', 'Admin\GiftsController@update')->name('admin.gifts.update');
Route::get('/admin/gifts/destroy/{id}', 'Admin\GiftsController@destroy');
Route::get('/admin/gifts/search', 'Admin\GiftsController@search')->name('gifts.search');

//reports
Route::get('/admin/reports/reportlist', 'Admin\StreamreportsController@reportlist')->name('admin.reports.reportlist');
Route::get('/admin/reports/create', 'Admin\StreamreportsController@create')->name('admin.reports.create');
Route::post('/admin/reports/create', 'Admin\ReportsController@store');
Route::get('/admin/reports/reportdestroy/{id}', 'Admin\StreamreportsController@reportdestroy');
Route::post('/admin/reports/reportsearch', 'Admin\StreamreportsController@reportsearch')->name('reports.reportsearch');
Route::get('/admin/reports/reportsearch', 'Admin\StreamreportsController@reportsearch');
Route::get('/admin/reports/show/{id}', 'Admin\StreamreportsController@show')->name('admin.reports.show');
Route::get('/admin/reports/changestatus/{id}/{status}/{rid}', 'Admin\StreamreportsController@changestatus');
Route::get('/admin/reports/changestatuslist/{id}/{status}', 'Admin\StreamreportsController@changestatuslist');
Route::get('/admin/reports/changestreamstatus/{id}/{status}/{rid}', 'Admin\StreamreportsController@changestreamstatus');

//subscriptions
Route::get('/admin/subscriptions', 'Admin\SubscriptionsController@index')->name('admin.subscriptions.index');
Route::get('/admin/subscriptions/create', 'Admin\SubscriptionsController@create')->name('admin.subscriptions.create');
Route::post('/admin/subscriptions/create', 'Admin\SubscriptionsController@store');
Route::get('/admin/subscriptions/show/{id}', 'Admin\SubscriptionsController@show')->name('admin.subscriptions.show');
Route::get('/admin/subscriptions/edit/{id}', 'Admin\SubscriptionsController@edit')->name('admin.subscriptions.edit');
Route::post('/admin/subscriptions/update/{id}', 'Admin\SubscriptionsController@update')->name('admin.subscriptions.update');
Route::get('/admin/subscriptions/destroy/{id}', 'Admin\SubscriptionsController@destroy');
Route::get('/admin/subscriptions/search', 'Admin\SubscriptionsController@search')->name('subscriptions.search');

// payments
Route::get('/admin/payments', 'Admin\PaymentsController@index')->name('admin.payments');
Route::get('/admin/payments/search', 'Admin\PaymentsController@search')->name('admin.payments.search');

// push notification
Route::get('/admin/notification', 'Admin\DashboardController@notification')->name('admin.notification');

// logout
Route::post('logout', function () {
    Auth::logout();
    return redirect('admin');
})->name('logout');

// streams
Route::get('/admin/streams', 'Admin\StreamsController@index')->name('admin.streams');
Route::get('/admin/streams/search', 'Admin\StreamsController@search')->name('admin.streams.search');
Route::get('/admin/streams/changestatus/{id}/{status}', 'Admin\StreamsController@changestatus');
Route::get('/admin/streams/userstreams/{id}', 'Admin\StreamsController@userstreams')->name('admin.userstreams');
Route::get('/admin/streams/streamsearch', 'Admin\StreamsController@streamsearch')->name('admin.streams.streamsearch');

//hashtags
Route::get('/admin/hashtags', 'Admin\HashtagsController@index')->name('admin.hashtags');
Route::get('/admin/hashtags/create', 'Admin\HashtagsController@create')->name('admin.hashtags.create');
Route::post('/admin/hashtags/create', 'Admin\HashtagsController@store');
Route::get('/admin/hashtags/edit/{id}', 'Admin\HashtagsController@edit')->name('admin.hashtags.edit');
Route::post('/admin/hashtags/update/{id}', 'Admin\HashtagsController@update')->name('admin.hashtags.update');
Route::get('/admin/hashtags/destroy/{id}', 'Admin\HashtagsController@destroy');
Route::get('/admin/hashtags/search', 'Admin\HashtagsController@search')->name('hashtags.search');

//invite friends
Route::get('/appstore/{id?}', 'Admin\AdminController@inviteearn')->name('admin.inviteearn');
