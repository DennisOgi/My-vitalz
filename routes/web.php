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

Route::get('/', 'loginController@login');
Route::post('/', 'loginController@login');
Route::get('/logout', 'loginController@logout');
Route::get('/signup', 'loginController@signup');
Route::post('/signup', 'loginController@signup');
Route::post('/signup-patient', 'loginController@signup_patient');
Route::get('/signup-patient', 'loginController@signup_patient');
Route::get('/signup-doctor', 'loginController@signup_doctor');
Route::post('/signup-doctor', 'loginController@signup_doctor');
Route::post('/signup-hospital', 'loginController@signup_hospital');
Route::get('/signup-hospital', 'loginController@signup_hospital');
Route::get('/signup-pharmacy', 'loginController@signup_pharmacy');
Route::post('/signup-pharmacy', 'loginController@signup_pharmacy');
Route::get('/signup-sales-rep', 'loginController@signup_sales_rep');
Route::post('/signup-sales-rep', 'loginController@signup_sales_rep');

Route::get('/dashboard', 'dashboardController@dashboard');
Route::post('/dashboard', 'dashboardController@dashboard');
Route::post('/dashboard-doctor', 'dashboardDoctorController@dashboard');
Route::get('/dashboard-doctor', 'dashboardDoctorController@dashboard');
Route::post('/dashboard-hospital', 'dashboardHospitalController@dashboard');
Route::get('/dashboard-hospital', 'dashboardHospitalController@dashboard');
// Pharmacy Dashboard Routes (using new PharmacyController)
Route::get('/dashboard-pharmacy', 'PharmacyController@dashboard');
Route::post('/dashboard-pharmacy', 'PharmacyController@dashboard');
// Sales Rep Dashboard Routes
Route::get('/dashboard-sales-rep', 'dashboardSalesRepController@dashboard');
Route::post('/dashboard-sales-rep', 'dashboardSalesRepController@dashboard');

// Pharmacy AJAX Actions
Route::post('/pharmacy/network/add', 'PharmacyController@addNetworkMember');
Route::post('/pharmacy/patient/register', 'PharmacyController@registerPatient');
Route::post('/pharmacy/settings/save', 'PharmacyController@saveSettings');
Route::post('/pharmacy/settings/discount', 'PharmacyController@updateDiscountPolicy');

// New Pharmacy Features (Phase 1)
Route::post('/pharmacy/profile/update', 'PharmacyController@profile');
Route::post('/pharmacy/appointment/accept', 'PharmacyController@appointments');
Route::post('/pharmacy/appointment/reschedule', 'PharmacyController@appointments');
Route::post('/pharmacy/appointment/reject', 'PharmacyController@appointments');
Route::post('/pharmacy/prescription/create', 'PharmacyController@newPrescription');
Route::post('/pharmacy/prescription/update', 'PharmacyController@editPrescription');
Route::post('/pharmacy/affiliate/approve', 'PharmacyController@affiliates');
Route::post('/pharmacy/affiliate/decline', 'PharmacyController@affiliates');

// Affiliate Network Management System
Route::post('/network/invite', 'NetworkController@sendInvitation');
Route::get('/network/invitations', 'NetworkController@viewInvitations');
Route::post('/network/invitation/accept', 'NetworkController@acceptInvitation');
Route::post('/network/invitation/decline', 'NetworkController@declineInvitation');
Route::post('/network/member/remove', 'NetworkController@removeMember');
Route::get('/network/members', 'NetworkController@viewMembers');
Route::post('/doctor/profile/toggle-public', 'NetworkController@togglePublicProfile');

// Affiliate Link Management
Route::get('/affiliate/links', 'AffiliateController@viewLinks');
Route::post('/affiliate/generate', 'AffiliateController@generateLink');
Route::post('/affiliate/toggle', 'AffiliateController@toggleLink');
Route::post('/affiliate/delete', 'AffiliateController@deleteLink');
Route::get('/affiliate/stats', 'AffiliateController@viewStats');
Route::post('/affiliate/copy', 'AffiliateController@copyLink');

// Doctor Virtual Pharmacy (doctors can manage their own virtual pharmacy space)
Route::get('/doctor/virtual-pharmacy', 'PharmacyController@doctorVirtual');
Route::post('/doctor/virtual-pharmacy/upgrade', 'PharmacyController@doctorUpgrade');
Route::post('/doctor/virtual-pharmacy/prescribe', 'PharmacyController@doctorCreatePrescription');
Route::get('/search-patients', 'dashboardDoctorController@search_patients');
Route::post('/search-patients', 'dashboardDoctorController@search_patients');
Route::get('/search-hospital', 'dashboardDoctorController@search_hospital');
Route::post('/search-hospital', 'dashboardDoctorController@search_hospital');
Route::get('/search-doctors', 'dashboardController@search_doctors');
Route::post('/search-doctors', 'dashboardController@search_doctors');
Route::get('/search-patients-h', 'dashboardHospitalController@search_patients');
Route::post('/search-patients-h', 'dashboardHospitalController@search_patients');
Route::get('/search-doctors-h', 'dashboardHospitalController@search_doctors');
Route::post('/search-doctors-h', 'dashboardHospitalController@search_doctors');
Route::get('/public-doctors', 'dashboardController@public_doctors');
Route::post('/public-doctors', 'dashboardController@public_doctors');
Route::post('/add-patients', 'dashboardDoctorController@add_patients');
Route::get('/add-patients', 'dashboardDoctorController@add_patients');
Route::post('/add-hospital', 'dashboardDoctorController@add_hospital');
Route::get('/add-hospital', 'dashboardDoctorController@add_hospital');
Route::post('/add-doctors', 'dashboardController@add_doctors');
Route::get('/add-doctors', 'dashboardController@add_doctors');
Route::post('/add-patients-h', 'dashboardHospitalController@add_patients');
Route::get('/add-patients-h', 'dashboardHospitalController@add_patients');
Route::post('/add-doctors-h', 'dashboardHospitalController@add_doctors');
Route::get('/add-doctors-h', 'dashboardHospitalController@add_doctors');
Route::get('/seen-notification', 'dashboardDoctorController@seen_notification');
Route::post('/seen-notification', 'dashboardDoctorController@seen_notification');
Route::get('/get-appointment-intervals', 'dashboardController@get_appointment_intervals');
Route::post('/get-appointment-intervals', 'dashboardController@get_appointment_intervals');
Route::post('/get-si-units', 'dashboardController@get_si_units');
Route::get('/get-si-units', 'dashboardController@get_si_units');
Route::get('/search-product', 'dashboardDoctorController@search_products');
Route::get('/check-compliance', 'dashboardDoctorController@check_compliance');
Route::get('/refer-to-doctor', 'dashboardDoctorController@refer_to_doctor');
Route::get('/search-refer-doctors', 'dashboardDoctorController@search_doctors');
Route::post('/search-refer-doctors', 'dashboardDoctorController@search_doctors');

Route::get('/add-to-cart', 'dashboardController@addToCart');
Route::get('/remove-from-cart', 'dashboardController@removeFromCart');
Route::get('/update-cart', 'dashboardController@updateCart');
Route::get('/shopping-cart', 'dashboardController@displayCart');
Route::post('/shopping-cart', 'dashboardController@displayCart');
Route::get('/show-cart', 'dashboardController@showCart');
Route::get('/payconfirm', 'dashboardController@payconfirm');

 //Clear route cache:
 Route::get('/route-clear', function() {
     $exitCode = Artisan::call('route:clear');
     return 'Routes cache cleared';
 });

 //Clear config cache:
 Route::get('/config-cache', function() {
     $exitCode = Artisan::call('config:cache');
     return 'Config cache cleared';
 }); 

// Clear application cache:
 Route::get('/clear-cache', function() {
     $exitCode = Artisan::call('cache:clear');
     return 'Application cache cleared';
 });

 // Clear view cache:
 Route::get('/view-clear', function() {
     $exitCode = Artisan::call('view:clear');
     return 'View cache cleared';
 });

// Manual Database Reset & Seed (For Debugging)
Route::get('/db-reset', function() {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => 'TestAccountsSeeder', '--force' => true]);
        return 'Database reset and seeded successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

