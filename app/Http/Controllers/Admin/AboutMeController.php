<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PersonalInfoModel;
use App\Models\SkillsCategoryModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AboutMeController extends Controller
{
    public function about_me() :View
    {
        $data = [];

        $personal_info = PersonalInfoModel::where('id',1)->first();
        if(!empty($personal_info)){
            $data['personal_info'] = $personal_info;
        }
        return view('about_me.about_me',$data);
    }

    public function save_personal_details(Request $request):JsonResponse
    {
        $sucess = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'full_name'             => 'required',
                'email'                 => 'required|email',
                'dob'                   => 'required',
                'availability'          => 'required',
                'work_started_from'     => 'required',
                'i_am_a'                => 'required',
                'about_me'              => 'required',
                'facebook_link'         => 'required',
                'linkedin_link'         => 'required',
                'instagram_link'        => 'required',
                'github_link'           => 'required',
            ];

            $messages = [
                'dob.required' => 'Date of birth is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                if (!Storage::exists('uploads')) {
                    // Create the directory
                    Storage::makeDirectory('uploads');
                }

                if (!File::exists(public_path(UPLOAD_PATH))) {
                    // Create the directory
                    File::makeDirectory(public_path(UPLOAD_PATH), $mode = 0755, true, true);
                }

                $data = [
                    'position'          =>  implode(',',$post['i_am_a']),
                    'full_name'         =>  $post['full_name'],
                    'email'             =>  $post['email'],
                    'date_of_birth'     =>  $post['dob'],
                    'Availablity'       =>  $post['availability'],
                    'work_started'      =>  $post['work_started_from'],
                    'total_experience'  =>  $post['total_experience'] ??  "",
                    'about_me'          =>  $post['about_me'],
                    'facebook_link'     =>  $post['facebook_link'],
                    'linkedin_link'     =>  $post['linkedin_link'],
                    'instagram_link'    =>  $post['instagram_link'],
                    'twitter_link'      =>  $post['twitter_link'] ?? '',
                    'github_link'       =>  $post['github_link'],
                    'whatshapp_link'    =>  $post['whatsapp_link'] ?? '',
                ];

                if ($request->hasFile('profile_logo')) {
                    $image_name =  strtolower($post['full_name']).'_'. time() . '.' . $request->profile_logo->extension();
                    $request->profile_logo->move(public_path(UPLOAD_PATH), $image_name);
                    $data['profile_logo'] = $image_name;
                }

                if ($request->hasFile('resume')) {
                    $image_name =  'resume_'. time() . '.' . $request->resume->extension();
                    $request->resume->move(public_path(UPLOAD_PATH), $image_name);
                    $data['resume'] = $image_name;
                }

                $info_id = PersonalInfoModel::updateOrCreate(
                    ['id' => 1], $data
                )->id;

                if(!empty($info_id)){
                    $sucess = true;
                    $status_code = 200;
                    $response_message = 'Saved Successfully';
                }
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('AboutMeController::save_personal_details() error');
        }

        return Response::json([
            'success' => $sucess,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }
}
