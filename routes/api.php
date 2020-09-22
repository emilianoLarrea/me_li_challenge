<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/prueba', function (Request $request) {
    $client = new \Google_Client();
    $client->setAuthConfig('/Users/emilianolarrea/Documents/Development/me_li_challenge/client_secret_223971220055-nd45goql7l79dqevkj1j8p72u5ku18ff.apps.googleusercontent.com.json');
    $client->addScope(Google_Service_Drive::DRIVE);
    $redirect_uri = 'http://127.0.0.1' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    echo json_encode($client->setRedirectUri($redirect_uri));
    return;
});