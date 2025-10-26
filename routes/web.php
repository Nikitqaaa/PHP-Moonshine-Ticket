<?php

use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArchiveAggregationController;
use App\Http\Controllers\CancelImpersonationController;
use MoonShine\Laravel\Http\Middleware\Authenticate;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', '/admin');

Route::middleware([...config('moonshine.middleware'), config('moonshine.auth.middleware')])
    ->get('/admin/auth/fake/logout', CancelImpersonationController::class)
    ->name('admin.auth.fake.logout');

Route::middleware([...config('moonshine.middleware')])
    ->get('/admin/archive/view/switch', [ArchiveAggregationController::class, 'switchYearView'])
    ->name('admin.archive.view.switch');

Route::group([
    'middleware' => [Authenticate::class, 'moonshine'],
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'name'       => 'admin.',
], function () {
    Route::get('server/{server}/create-token', [ServerController::class, 'createToken'])
        ->name('server.create-token');
    Route::post('server/{server}/delete-token', [ServerController::class, 'destroyToken'])
        ->name('server.delete-token');
    Route::post('server/{server}/delete-all-token', [ServerController::class, 'destroyAll'])
        ->name('server.delete-all-token');
});
