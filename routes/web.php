<?php

use App\Http\Controllers\LoginController;
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

Route::get('/',[LoginController::class,'login_page'])->name('login');
Route::post('/user-login',[LoginController::class,'user_login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/connection', [LoginController::class, 'check_connection'])->name('check_connection');

Route::middleware('AuthMiddleware')->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('about-me',[AboutMeController::class,'about_me'])->name('about_me');
    Route::post('save-personal-details',[AboutMeController::class,'save_personal_details'])->name('save_personal_details');

    // Skill Start
    Route::get('skills-category',[SkillsController::class,'skills_category_page'])->name('skills_category');
    Route::get('skills-category-list',[SkillsController::class,'skills_category_list'])->name('skills_category_list');
    Route::post('save-skills-category',[SkillsController::class,'save_skill_category'])->name('save_skill_category');
    Route::get('get-skill-category-details/{id}',[SkillsController::class,'get_skill_category_details'])->name('get_skill_category_details');

    Route::get('skills',[SkillsController::class,'skills_page'])->name('skills_page');
    Route::get('skills-list',[SkillsController::class,'skills_list'])->name('skills_list');
    Route::post('save-skills',[SkillsController::class,'save_skills'])->name('save_skills');
    Route::get('get-skill-details/{id}',[SkillsController::class,'get_skill_details'])->name('get_skill_details');
    Route::post('skills/delete-skill/{id}', [SkillsController::class, 'delete_skill'])->name('delete_skill');
    // Skill End

    // Experience Start
    Route::get('experience',[ExperienceController::class,'experience_page'])->name('experience');
    Route::get('experience-list',[ExperienceController::class,'experience_list'])->name('experience_list');
    Route::get('experience-details/{id?}',[ExperienceController::class,'experience_details'])->name('experience_details');
    Route::post('save-experience',[ExperienceController::class,'save_experience'])->name('save_experience');
    Route::post('experience/delete-experience/{id}', [ExperienceController::class, 'delete_experience'])->name('delete_experience');
    Route::post('experience/delete-experience-document/{id}', [ExperienceController::class, 'delete_exp_document'])->name('delete_exp_document');
    Route::get('get-company-document/{id}', [ExperienceController::class, 'getCompanyDocument'])->name('getCompanyDocument');
    // Experience End

    // Education Start
    Route::get('education',[EducationController::class,'education_page'])->name('education_page');
    Route::get('education-details/{id?}',[EducationController::class,'education_details'])->name('education_details');
    Route::get('education-list',[EducationController::class,'education_list'])->name('education_list');
    Route::post('save-education',[EducationController::class,'save_education'])->name('save_education');
    Route::post('delete-education/{id}',[EducationController::class,'delete_education'])->name('delete_education');
    // Education End


    // Experience Start
    Route::get('project-category',[ProjectsController::class,'project_category_page'])->name('project_category_page');
    Route::get('project-category-list',[ProjectsController::class,'project_category_list'])->name('project_category_list');
    Route::post('save-project-category',[ProjectsController::class,'save_project_category'])->name('save_project_category');
    Route::get('get-project-category-details/{id}',[ProjectsController::class,'get_project_category_details'])->name('get_project_category_details');

    Route::get('projects',[ProjectsController::class,'projects_page'])->name('projects_page');
    Route::get('projects/details/{id?}',[ProjectsController::class,'project_details'])->name('project_details');
    Route::get('projects-list',[ProjectsController::class,'projects_list'])->name('projects_list');
    Route::post('save-project-details',[ProjectsController::class,'save_projects'])->name('save_project_details');
    Route::post('delete-project/{id}', [ProjectsController::class, 'delete_project'])->name('delete_project');
    // Experience End

    Route::get('error-logs', [ErrorLogController::class, 'error_logs_page'])->name('error_logs_page');
    Route::get('error-logs-list', [ErrorLogController::class, 'error_logs_list'])->name('error_logs_list');
    Route::post('delete-error-logs-list', [ErrorLogController::class, 'delete_error_logs_list'])->name('delete_error_logs_list');
});
