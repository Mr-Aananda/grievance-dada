<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    BuyerController,
    DashboardController,
    RoleController,
    UserController,
    DepartmentController,
    SectionController,
    CategoryController,
    ComplainTypeController,
    ComplainController,
    GrievanceController,
    ReportController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [GrievanceController::class, 'index']);

Route::resource('grievance', GrievanceController::class)
    ->only(['index', 'store', 'show']);

Route::get('/grievance-media/{media}', [GrievanceController::class, 'stream'])
    ->name('grievance.media.stream');

// Route::get('/', fn() => redirect()->route('login'));

Route::view('/maintenance', 'maintenance-page')->name('maintenance');
Route::view('/help-and-support', 'help-and-support')->name('help-and-support');

/*
|--------------------------------------------------------------------------
| Admin Routes (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'permission.add'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/dashboard/monthly-complains', [DashboardController::class, 'getMonthlyComplains'])
            ->name('dashboard.monthly-stats');

        /*
        |-------------------------
        | Role & User
        |-------------------------
        */
        Route::resource('role', RoleController::class);

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('sections/{department}', [UserController::class, 'getSections'])
                ->name('sections');
            Route::resource('/', UserController::class)
                ->parameters(['' => 'user']);
        });

        /*
        |-------------------------
        | Department
        |-------------------------
        */
        Route::prefix('department')->name('department.')->group(function () {
            Route::get('trash', [DepartmentController::class, 'trash'])->name('trash');
            Route::get('restore/{id}', [DepartmentController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [DepartmentController::class, 'permanentDelete'])
                ->name('permanentDelete');
        });
        Route::resource('department', DepartmentController::class);

        /*
        |-------------------------
        | Section
        |-------------------------
        */
        Route::prefix('section')->name('section.')->group(function () {
            Route::get('trash', [SectionController::class, 'trash'])->name('trash');
            Route::get('restore/{id}', [SectionController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [SectionController::class, 'permanentDelete'])
                ->name('permanentDelete');
        });
        Route::resource('section', SectionController::class);

        /*
        |-------------------------
        | Category
        |-------------------------
        */
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('trash', [CategoryController::class, 'trash'])->name('trash');
            Route::get('restore/{id}', [CategoryController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [CategoryController::class, 'permanentDelete'])
                ->name('permanentDelete');
        });
        Route::resource('category', CategoryController::class);

        /*
        |-------------------------
        | Complain Type
        |-------------------------
        */
        Route::prefix('complain-type')->name('complain-type.')->group(function () {
            Route::get('trash', [ComplainTypeController::class, 'trash'])->name('trash');
            Route::get('restore/{id}', [ComplainTypeController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [ComplainTypeController::class, 'permanentDelete'])
                ->name('permanentDelete');
        });
        Route::resource('complain-type', ComplainTypeController::class);

        /*
       |-------------------------
       | Buyer
       |-------------------------
       */
        Route::prefix('buyer')->name('buyer.')->group(function () {
            Route::get('trash', [BuyerController::class, 'trash'])->name('trash');
            Route::get('restore/{id}', [BuyerController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [BuyerController::class, 'permanentDelete'])
                ->name('permanentDelete');

            Route::patch('toggle-status/{id}', [BuyerController::class, 'toggleStatus'])->name('toggleStatus');
            Route::get('/import-page', [BuyerController::class, 'importPage'])->name('import-page');
            Route::post('/import', [BuyerController::class, 'import'])
                ->name('import');
            Route::get('/export', [BuyerController::class, 'export'])
                ->name('export');


        });
        Route::resource('buyer', BuyerController::class);

        /*
|--------------------------------------------------------------------------
| COMPLAIN ROUTES
|--------------------------------------------------------------------------
*/
        Route::prefix('complain')->name('complain.')->group(function () {

            Route::get('manual', [ComplainController::class, 'manual'])->name('manual');

            Route::get('trash', [ComplainController::class, 'trash'])->name('trash');
            Route::post('restore/{id}', [ComplainController::class, 'restore'])->name('restore');
            Route::delete('permanent-delete/{id}', [ComplainController::class, 'permanentDelete'])
                ->name('permanentDelete');

            /* IMAGE STREAM */
            Route::get(
                '{complain}/image/{image}/stream',
                [ComplainController::class, 'streamImage']
            )->name('image.stream');

            /* FILE DOWNLOAD */
            Route::get(
                '{complain}/file/{file}/download',
                [ComplainController::class, 'downloadFile']
            )->name('file.download');

            /* VIDEO STREAM */
            Route::get(
                '{complain}/video/{video}/stream',
                [ComplainController::class, 'streamVideo']
            )->name('videos.stream');

            Route::get(
                '{complain}/download-all',
                [ComplainController::class, 'downloadAllAttachments']
            )->name('download.all');

            Route::post(
                '{complain}/update-status',
                [ComplainController::class, 'updateStatus']
            )->name('update-status');

            Route::resource('/', ComplainController::class)
                ->parameters(['' => 'complain']);
        });

        /*
        |-------------------------
        | Reports
        |-------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/overall-report', [ReportController::class, 'overallReport'])
                ->name('overall-report');
            Route::post('/overall-report/export', [ReportController::class, 'exportReport'])
                ->name('overall.export');
        });
    });

/*
|--------------------------------------------------------------------------
| Utility
|--------------------------------------------------------------------------
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully';
});

require __DIR__ . '/auth.php';
