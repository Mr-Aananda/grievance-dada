<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
    RoleController,
    UserController,
    DepartmentController,
    SectionController,
    CategoryController,
    GrievanceController
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
        |--------------------------------------------------------------------------
        | ADMIN GRIEVANCES ROUTES
        |--------------------------------------------------------------------------
        */
        Route::prefix('grievances')->name('admin.grievance.')->group(function () {
            Route::get('/', [GrievanceController::class, 'adminIndex'])->name('index');
            Route::get('/{id}', [GrievanceController::class, 'adminShow'])->name('show');
            Route::post('/{id}/update-status', [GrievanceController::class, 'adminUpdateStatus'])->name('update-status');
            Route::delete('/{id}', [GrievanceController::class, 'adminDestroy'])->name('destroy');
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

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn', 'ko'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

require __DIR__ . '/auth.php';
