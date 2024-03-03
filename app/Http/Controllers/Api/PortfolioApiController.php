<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EducationModel;
use App\Models\ExperienceDocumentModel;
use App\Models\ExperienceModel;
use App\Models\PersonalInfoModel;
use App\Models\ProjectCategoryModel;
use App\Models\ProjectsModel;
use App\Models\SkillsCategoryModel;
use App\Models\SkillsMasterModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class PortfolioApiController extends Controller
{
    public function personal_info(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $data = PersonalInfoModel::first();

            if (!empty($data)) {
                $data->profile_logo = Helpers::firebase_img_url($data->profile_logo);
                $data->resume = Helpers::firebase_img_url($data->resume);

                $success = true;
                $response_message = '';
            } else {
                $data = [];
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::personal_info() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function skills(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $skillData = SkillsMasterModel::join('skills_category as sc','sc.id','skills_master.skills_category_id')
                ->select(['skills_master.name as skill','skills_master.logo','sc.category_name as skill_category'])
                ->get();

            if ($skillData->isNotEmpty()) {
                foreach ($skillData as $skill){
                    if(!isset($data[$skill['skill_category']])){
                        $data[$skill['skill_category']] = [];
                    }

                    $data[$skill['skill_category']][] = [
                        'skill' =>  $skill['skill'],
                        'logo' =>  Helpers::firebase_img_url($skill['logo'])
                    ];
                }
                $success = true;
                $response_message = '';
            } else {
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::skills() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function experience(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $expData = ExperienceModel::get();

            if ($expData->isNotEmpty()) {
                $experienceData = [];
                $document_path = asset(COMPANY_DOCUMENT_PATH).'/';

                foreach ($expData as $experience){
                    if(!isset($data[$experience['company_name']])){
                        $experienceData[$experience['company_name']] = [];
                    }

                    $experienceData[$experience['company_name']] = [
                        'company_logo'  =>  Helpers::firebase_img_url($experience['company_logo']),
                        'company_name'  =>  $experience['company_name'],
                        'role'          =>  $experience['role'],
                        'started'       =>  $experience['start'],
                        'ended'         =>  $experience['end'],
                        'description'   =>  $experience['description'],
                        'skills'        =>  $experience['skills'],
                        'documents'     => ExperienceDocumentModel::select(['file_name as document_name'])
                                                ->where('experience_id', $experience['id'])
                                                ->get()
                                                ->map(function ($doc) {
                                                    return [
                                                        'document_name' => Helpers::firebase_img_url($doc->document_name),
                                                    ];
                                                })
                                                ->toArray()
                    ];
                }

                $data = array_values($experienceData);
                $success = true;
                $response_message = '';
            } else {
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::skills() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function social_links(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $data = PersonalInfoModel::select(['full_name as name','facebook_link as facebook','linkedin_link as linkedin','instagram_link as instagram','twitter_link as twitter','github_link as github','whatshapp_link as whatshapp'])->first();

            if (!empty($data)) {
                $success = true;
                $response_message = '';
            } else {
                $data = [];
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::social_links() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function education(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $data = EducationModel::select(['logo','institute_name','degree','started','ended','grade','description'])
                ->get()
                ->map(function ($doc) {
                    $doc->logo = Helpers::firebase_img_url($doc->logo);
                    return $doc;
                });

            if ($data->isNotEmpty()) {
                $success = true;
                $response_message = '';
            } else {
                $data = [];
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::education() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function projects(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {

            $data['project'] = ProjectsModel::join('project_category','project_category.id','projects.project_category_id')
                ->select(['project_category.category','image','title','language_used','started','ended','description','github','webapp'])
                ->get()
                ->map(function ($doc) {
                    $doc->image = Helpers::firebase_img_url($doc->image);
                    return $doc;
                });

            if ($data['project']->isNotEmpty()) {

                $category = [];
                foreach ($data['project'] as $value){
                    if(!isset($category[$value['category']])){
                        $category[$value['category']] = '';
                    }

                    $category[$value['category']] = $value['category'];
                }
                $data['category'] = array_values($category);
                $success = true;
                $response_message = '';
            } else {
                $data = [];
                $response_message = 'No Data Found.';
            }
        }catch (\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical('PortfolioApiController::projects() Error');
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }
}
