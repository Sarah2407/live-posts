<?php

use App\Events\ChatMessageEvent;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/reset-password', function ($token) {
    return $token;
})
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.reset');

Route::get('/shared/posts/{post}', function(Request $request, Post $post){
    return "Specifically just made for you Post id: {$post->id}";
})->name('shared.post')->middleware('signed');

Route::get('/ws', function () {
    return view('websocket');
});

Route::post('/chat-message', function(Request $request){
    event(new ChatMessageEvent($request->message)); 
    return null;
});