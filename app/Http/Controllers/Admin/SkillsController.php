<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\SkillsCategoryModel;
use App\Models\SkillsMasterModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    // Skill Category Start
    public function skills_category_page(): View
    {
        return view('skills.skills_category');
    }

    public function skills_category_list(Request $request):JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $data['result'] = SkillsCategoryModel::orderBy('id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($row) {
                        return date('d M, Y h:i A', strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        // <a href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp delete_skill_category" data-category-id="'.$row->id.'"><i class="fa fa-trash"></i></a>
                        return '<div class="d-flex">
                                        <a href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 edit_skill_category" data-category-id="'.$row->id.'"><i class="fas fa-pencil-alt"></i></a>
                                </div>';
                    })
                    ->rawColumns(['created_at','action','created_by'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('SkillsController::skills_category_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_skill_category(Request $request):JsonResponse
    {
        $sucess = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'category_name' => isset($request->skills_category_id) && !empty($request->skills_category_id) ? 'required|unique:skills_category,category_name,'.$request->skills_category_id : 'required|unique:skills_category,category_name',
            ];

            $messages = [
                'category_name.required' => 'Category is required',
                'category_name.unique' => 'Category Already Exist',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                $data = [
                    'category_name' => $post['category_name']
                ];

                $message = "Category Successfully Created";

                if(isset($request->skills_category_id) && !empty($request->skills_category_id)){
                    $category = SkillsCategoryModel::find($request->skills_category_id);
                    $category->update($data);
                    $message = "Category Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $category = SkillsCategoryModel::create($data);
                }

                if($category){
                    $sucess = true;
                    $status_code = 200;
                    $response_message = $message;
                }
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('SkillsController::save_skill_category() error');
        }

        return Response::json([
            'success' => $sucess,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }

    public function get_skill_category_details($category_id) :JsonResponse
    {
        Log::info("SkillsController::get_skill_category_details() Start");

        $success = false;
        $status_code = 500;
        $data = [];
        $response_message = "something went wrong please try again.";

        try {
            $data = SkillsCategoryModel::where('id',$category_id)->get()->first();

            $success = true;
            $status_code = 200;
            $response_message ='';
        }catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical("SkillsController::get_skill_category_details() Error");
            Log::critical($e);
        }

        Log::info("SkillsController::get_skill_category_details() End");
        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }
    // Skill Category End

    // Skill Master Start
    public function skills_page(): View
    {
        $data['skillCategoryList'] = SkillsCategoryModel::pluck('category_name','id');
        return view('skills.skills',$data);
    }

    public function skills_list(Request $request):JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $query = SkillsMasterModel::join('skills_category','skills_category.id','skills_master.skills_category_id')
                ->select(['skills_master.id as skill_id','skills_category.category_name','skills_master.name as skill','skills_master.logo','skills_master.created_at']);

            $data['result'] = $query->orderBy('skills_master.id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('logo', function ($row) {
                        $logo = Helpers::firebase_img_url($row->logo);
                        return '<img src="'.($logo).'" width="70" alt="">';
                    })
                    ->addColumn('created_at', function ($row) {
                        return date('d M, Y h:i A', strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="d-flex">
                                        <a href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 edit_skill" data-skill-id="'.$row->skill_id.'"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/skills/delete-skill/'.$row->skill_id.'" class="btn btn-danger shadow btn-xs sharp delete_skill" data-skill-id="'.$row->skill_id.'"><i class="fa fa-trash"></i></a>
                                    </div>';
                    })
                    ->rawColumns(['logo','created_at','action','created_by'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('SkillsController::skills_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_skills(Request $request):JsonResponse
    {
        $sucess = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'skill_name'        => isset($request->skills_id) && !empty($request->skills_id) ? 'required|unique:skills_master,name,'.$request->skills_id : 'required|unique:skills_master,name',
                'skill_category'    => 'required|exists:skills_category,id',
                'logo'              => isset($request->skills_id) && !empty($request->skills_id) ? '' : 'required',
            ];

            $messages = [
                'skill_name.required' => 'Skill Name is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                $data = [
                    'skills_category_id'    => $post['skill_category'],
                    'name'                  => $post['skill_name'],
                ];

                if ($request->hasFile('logo')) {
                    $image = $request->file('logo');
                    $data['logo'] = Helpers::save_img_firebase('Skills',$image,$post['skill_name']);
                }

                $message = "Skills Saved Sucessfully";

                if(isset($request->skills_id) && !empty($request->skills_id)){
                    $category = SkillsMasterModel::find($request->skills_id);
                    $category->update($data);
                    $message = "Category Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $category = SkillsMasterModel::create($data);
                }

                if($category){
                    $sucess = true;
                    $status_code = 200;
                    $response_message = $message;
                }
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('SkillsController::save_skill_category() error');
        }

        return Response::json([
            'success' => $sucess,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }

    public function get_skill_details($skill_id) :JsonResponse
    {
        Log::info("SkillsController::get_skill_details() Start");

        $success = false;
        $status_code = 500;
        $data = [];
        $response_message = "something went wrong please try again.";

        try {
            $data = SkillsMasterModel::where('id',$skill_id)->get()->first();
            $data['logo'] = Helpers::firebase_img_url($data['logo']);

            $success = true;
            $status_code = 200;
            $response_message ='';
        }catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical("SkillsController::get_skill_details() Error");
            Log::critical($e);
        }

        Log::info("SkillsController::get_skill_details() End");
        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function delete_skill($skill_id): JsonResponse
    {
        $Delete_data = SkillsMasterModel::find($skill_id);

        Helpers::delete_firebase_img($Delete_data->logo);
        $Delete_data->delete();

        Log::info('SkillsController::delete_skill() isset($Delete_data)');
        if (isset($Delete_data)) {
            Log::info("true\n\n");

            return response()->json(['status' => true, 'message' => 'Skill Removed successfully']);
        } else {
            Log::info("false\n\n");

            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
    // Skill Master End
}
