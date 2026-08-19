<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('organizations', OrganizationController::class)
        ->except(['create', 'edit']);
});

Route::middleware('auth')->group(function () {
    Route::resource('organizations', OrganizationController::class)
        ->except(['create', 'edit']);

    Route::get(
        'organizations/{organization}/members',
        [OrganizationMemberController::class, 'index']
    )->name('organizations.members.index');

    Route::post(
        'organizations/{organization}/members',
        [OrganizationMemberController::class, 'store']
    )->name('organizations.members.store');

    Route::patch(
        'organizations/{organization}/members/{user}',
        [OrganizationMemberController::class, 'update']
    )->name('organizations.members.update');

    Route::delete(
        'organizations/{organization}/members/{user}',
        [OrganizationMemberController::class, 'destroy']
    )->name('organizations.members.destroy');
});

Route::middleware('auth')->get('/test-authorization/{organization}', function (
    \App\Models\Organization $organization
) {
    \Illuminate\Support\Facades\Gate::authorize(
        'update',
        $organization
    );

    return 'You are authorized.';
});

require __DIR__ . '/auth.php';
