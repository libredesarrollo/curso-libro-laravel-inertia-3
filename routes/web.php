<?php

use App\Http\Controllers\Contact\CompanyController;
use App\Http\Controllers\Contact\DetailController;
use App\Http\Controllers\Contact\GeneralController;
use App\Http\Controllers\Contact\PersonController;
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
        // sleep(2);
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

   // PASO POR PASO
Route::group([
    'prefix' => 'contact',
    ],function () {
        Route::resource('contact-general', GeneralController::class)->only(['create', 'edit', 'store', 'update']);
        Route::resource('contact-company', CompanyController::class)->only(['create','edit','store','update']);
        Route::resource('contact-person', PersonController::class)->only(['create','edit','store','update']);
        Route::resource('contact-detail', DetailController::class)->only(['create','edit','store','update']);
});

// BLOG
Route::group([
    'prefix' => 'blog',
], function () {
    Route::get('/', [App\Http\Controllers\Blog\PostController::class, 'index'])->name('web.index');
    Route::get('/{post:slug}', [App\Http\Controllers\Blog\PostController::class, 'show'])->name('web.show');
    // POSTS - Infinite Scroll Demo
Route::get('/infinite-scroll/posts', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
});

// SHOP
Route::group([
    'prefix' => 'shop',

], function () {
    Route::get('/', [App\Http\Controllers\Shop\CartController::class, 'index'])->name('shop.index');
    Route::post('/add/{post}/{count}', [App\Http\Controllers\Shop\CartController::class, 'add'])->name('shop.add');
});

// TODO
Route::middleware(
    [
        'middleware' => 'auth',
        'verified'
    ]
)->prefix('todo')->group(function () {
    Route::get('/', [App\Http\Controllers\TodoController::class, 'index'])->name('todo.index');
    Route::post('/store', [App\Http\Controllers\TodoController::class, 'store'])->name('todo.store');
    Route::put('/update/{todo}', [App\Http\Controllers\TodoController::class, 'update'])->name('todo.update');
    Route::delete('/destroy/{todo?}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('todo.destroy');
    Route::post('/status/{todo}', [App\Http\Controllers\TodoController::class, 'status'])->name('todo.status');
    Route::post('/order', [App\Http\Controllers\TodoController::class, 'order'])->name('todo.order');
});



require __DIR__.'/settings.php';
