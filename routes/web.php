<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Yaml\Yaml;

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
    return view('swagger');
})->name('homepage');

Route::get('/docs/open-api.{filename}', function ($filename) {

    $docsFile = resource_path('docs/open-api.yaml');

    if ($filename == 'json') {
        return response()->json(Yaml::parseFile($docsFile));
    } else if ($filename == 'yaml') {
        return response()->file($docsFile, ['Content-Type' => 'applicaton/yaml']);
    }

    return abort(404);
});
