<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SolutionController;
use App\Models\Declaration;
use App\Models\Solution;

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

$controller_path = 'App\Http\Controllers';

// Main Page Route
// Route::get('/', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');

Route::get('/', [AuthenticationController::class, 'viewDashboard'])->middleware('auth')->name('home');


Route::get('test', function () {


    return Declaration::find(2)->categorie()->pluck('name')[0];

    // return Solution::find(6)->pic()->get();


    // $data = Declaration::with('categorie.service')->whereHas('categorie.service', function ($q) {
    //     $q->where('idChefService', '4');
    // })->get();

    // return $data;
});

Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {
    Route::get('new', [ReportController::class, 'viewNewReport'])->name('report.new.view');
    Route::get('current', [ReportController::class, 'viewCurrentReport'])->name('report.current.view');
    Route::get('solved', [ReportController::class, 'viewSolvedReport'])->name('report.solved.view');
    Route::get('me', [ReportController::class, 'viewMyReport'])->name('myreport.view');


    Route::get('current/service', [ReportController::class, 'viewServiceCurrentReport'])->name('service.report.current.view');
    Route::get('solved/service', [ReportController::class, 'viewServiceSolvedReport'])->name('service.report.solved.view');
    Route::get('create', [ReportController::class, 'viewCreateReport'])->name('report.create.view');
    Route::post('create', [ReportController::class, 'create'])->name('report.create');
    Route::post('validate', [ReportController::class, 'makeValide'])->name('report.validate');
    Route::post('reject', [ReportController::class, 'reject'])->name('report.reject');
    Route::get('/{id}', [ReportController::class, 'viewReportDetail']);
});
Route::group(['prefix' => 'users'], function () {
    Route::get('/simple', [ReportController::class, 'viewSimpleUsers'])->name('users.simple.view');
    Route::get('/technical', [ReportController::class, 'viewTechnicalUsers'])->name('users.technical.view');
});

Route::group(['prefix' => 'solution', 'middleware' => ['auth']], function () {
    Route::post('/create', [SolutionController::class, 'create'])->name('solution.create');
    Route::post('/accepte', [SolutionController::class, 'accepte'])->name('solution.accepte');
    Route::post('/rejecte', [SolutionController::class, 'rejecte'])->name('solution.rejecte');
    Route::post('/complete', [SolutionController::class, 'complete'])->name('solution.complete');
});

Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');
Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthenticationController::class, 'viewLogin'])->name('login.view');
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
});

Route::group(['prefix' => 'account'], function () {
    Route::get('/settings', [AccountController::class, 'viewSettings'])->middleware('auth')->name('account.settings.view');
    Route::post('/settings', [AccountController::class, 'editAccountInfo'])->name('account.edit');
});

// layout
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', $controller_path . '\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');
