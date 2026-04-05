<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        sleep(2);
        Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');

    // DASHBOARD
    Route::group(['prefix' => 'dashboard'], function (){
        Route::resource('category', CategoryController::class);
        Route::resource('post', PostController::class);
        Route::resource('tag', TagController::class);
        Route::post('post/{post}/upload', [PostController::class,'upload'])->name('post.upload');
        Route::delete('post/{post}/image/delete', [App\Http\Controllers\Dashboard\PostController::class, 'imageDelete'])->name('post.image-delete');
    }); 
    
});

require __DIR__.'/settings.php';
