<?php

use App\Events\LocationUpdated;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pusher', function () {
    $location = [
        'latitude' => 37.7749,
        'longitude' => -122.4194,
        'timestamp' => now(),
        'agent_id' => 1
    ];

    broadcast(new LocationUpdated($location));

    return response()->json(['status' => 'Event sent to Pusher']);
});

Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('reset.password.get');
Route::post('password/reset', [PasswordResetController::class, 'resetPassword'])->name('reset.password.post');

