<?php

use App\Http\Controllers\Api\PortfolioApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('personal-info',[PortfolioApiController::class,'personal_info'])->name('personal_info');
Route::get('skills',[PortfolioApiController::class,'skills'])->name('skills');
Route::get('experience',[PortfolioApiController::class,'experience'])->name('experience');
Route::get('social-links',[PortfolioApiController::class,'social_links'])->name('social_links');
Route::get('education',[PortfolioApiController::class,'education'])->name('education');
Route::get('projects',[PortfolioApiController::class,'projects'])->name('projects');
Route::get('check-lead',[PortfolioApiController::class,'check_lead'])->name('check_lead');
Route::post('save-lead-contact',[PortfolioApiController::class,'save_lead_contact'])->name('save_lead_contact');
