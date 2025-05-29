<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubjectController;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http as Https;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('posts', PostController::class)
    ->middleware(['auth', 'verified'])
    ->names([
        'index' => 'posts.index',
        'create' => 'posts.create',
        'store' => 'posts.store',
        'show' => 'posts.show',
        'edit' => 'posts.edit',
        'update' => 'posts.update',
        'destroy' => 'posts.destroy',
    ]);

Route::resource('markers', MarkerController::class)
    ->middleware(['auth', 'verified'])
    ->names([
        'index' => 'markers.index',
        'create' => 'markers.create',
        'store' => 'markers.store',
        'show' => 'markers.show',
        'edit' => 'markers.edit',
        'update' => 'markers.update',
        'destroy' => 'markers.destroy',
    ]);

Route::post("/comment/{post}", [CommentController::class, "store"])->name("comments.store")->middleware("auth");

Route::resource('comments', CommentController::class)->only(['destroy']);

Route::get("/products", [ProductController::class, "index"])->middleware("auth")->name("products.index");

Route::controller(CartController::class)
    ->middleware('auth')
    ->prefix('/cart')
    ->name('cart.')
    ->group(function () {
        Route::post('/add/{product}', 'add')->name('add');
        Route::get('/', 'view')->name('checkout');
        Route::post('/clear', 'clear')->name('clear');
        Route::post('/update', 'update')->name('update');
    });

Route::get("subjects", [SubjectController::class, "index"])->name("subjects.index");

Route::get("display-subjects", function () {

    $datasets = [
        "andrus" => [
            "href" => "https://hajus.ta23raamat.itmajakas.ee/api/movies",
            "custom_fields" => ["director", "release_year"],
        ],

        "kert" => [
            "href" => "https://hajus.tak23mand.itmajakas.ee/api/favourite/07b4cc44-9042-4944-b1dc-56eac757ca4f",
            "custom_fields" => ["genre", "devloper"],
        ],

        "katrin" => [
            "href" => "https://hajusrakendused.ta23ansper.itmajakas.ee/subjects",
            "custom_fields" => ["ranking", "rating"],
        ],
        "jaanika" => [
            "href" => "https://hajusrakendused.ta23teearu.itmajakas.ee/subjects",
            "custom_fields" => ["seats", "comfort"],
        ],
    ];

    $data = match (request("type")) {
        "andrus" => Https::get($datasets["andrus"]["href"])->json(),
        "kert" => Https::get($datasets["kert"]["href"])->json(),
        "katrin" => Https::get($datasets["katrin"]["href"])->json(),
        "jaanika" => Https::get($datasets["jaanika"]["href"])->json(),
        default => Subject::all()->toArray(),
    };


    return Inertia::render("Subjects", [
        "data" => $data,
        'customFields' => data_get($datasets, request('type') . '.custom_fields', ['ranking', 'price']),
    ]);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
