<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('create',function(){

    return Inertia::render('Socket/Create',[]);
});


Route::post('broadcast',function(Request $request){
   try{
        // get event name
        $channel = $request->input('channel');
        $event_name = $request->input('event_name');
        $event_data = $request->input('event_data');
        

        $eventClass = "App\\Events\\" . $event_name;
        if (!class_exists($eventClass)) {
            throw new Exception('evnet does not exist');
        } 

        //fire event
        event(new $eventClass($event_data));
        
        return ['done'=>true];

   }catch(Exception $e){
        return ['done'=>false];
   }


});


Route::get('show',function(){
    return Inertia::render('Socket/Show',[]);
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});






Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
