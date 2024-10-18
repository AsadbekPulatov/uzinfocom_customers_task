<?php

use App\Http\Controllers\ProfileController;
use App\Jobs\ExportCustomerJob;
use Illuminate\Support\Facades\Route;
use PhpAmqpLib\Connection\AMQPStreamConnection;

Route::get('/', function () {
    dd(gethostname());
    return view('welcome');
});

Route::view('/download/test/my/telescope', 'telescope');

Route::get('/download',function (){
   ExportCustomerJob::dispatch();
   return 'started';
});

Route::get('/download/{link}', function ($link){
    return response()->download(public_path('storage/'.$link));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
