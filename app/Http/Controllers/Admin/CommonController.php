<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\LeadContactModel;
use App\Models\PersonalInfoModel;
use App\Models\ProjectsModel;
use App\Models\SkillsMasterModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function dashboard_api() :JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $data = [];

        try {
            $data['skills'] = SkillsMasterModel::count() ?? 0;
            $data['projects'] = ProjectsModel::count() ?? 0;
            $data['experience'] = PersonalInfoModel::pluck('total_experience')->first() ?? '-';
            $data['messages'] = LeadContactModel::count();

            $success = true;
            $response_message = '';

        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('CommonController::dashboard_api() error');
        }

        return Response::json([
            'success'   => $success,
            'message'   => $response_message,
            'data'      => $data,
        ],$status_code);
    }
}
