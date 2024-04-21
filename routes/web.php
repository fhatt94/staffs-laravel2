<?php
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Define the chirps resource route within its own middleware group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('chirps', ChirpController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);

    // Additional chirps routes
    Route::post(
        '/chirps/{chirp}/addToFavourites',
        [ChirpController::class, 'addToFavourites']
    )->name('chirps.favourites.add');
    Route::get(
        '/chirps/favourites',
        [ChirpController::class, 'favourites']
    )->name('chirps.favourites');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
