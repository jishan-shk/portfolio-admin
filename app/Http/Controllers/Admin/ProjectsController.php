<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ProjectCategoryModel;
use App\Models\ProjectsModel;
use App\Models\SkillsMasterModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProjectsController extends Controller
{
    // Project Category Start
    public function project_category_page(): View
    {
        return view('project.project_category');
    }

    public function project_category_list(Request $request):JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $data['result'] = ProjectCategoryModel::orderBy('id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($row) {
                        return date('d M, Y h:i A', strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        // <a href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp delete_skill_category" data-category-id="'.$row->id.'"><i class="fa fa-trash"></i></a>
                        return '<div class="d-flex">
                                        <a href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 edit_project_category" data-category-id="'.$row->id.'"><i class="fas fa-pencil-alt"></i></a>
                                </div>';
                    })
                    ->rawColumns(['created_at','action','created_by'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('ProjectsController::project_category_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_project_category(Request $request):JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'category' => isset($request->project_category_id) && !empty($request->project_category_id) ? 'required|unique:project_category,category,'.$request->project_category_id : 'required|unique:project_category,category',
            ];

            $messages = [
                'category.required' => 'Category is required',
                'category.unique' => 'Category Already Exist',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                $data = [
                    'category' => $post['category']
                ];

                $message = "Category Successfully Created";

                if(isset($request->project_category_id) && !empty($request->project_category_id)){
                    $category = ProjectCategoryModel::find($request->project_category_id);
                    $category->update($data);
                    $message = "Category Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $category = ProjectCategoryModel::create($data);
                }

                if($category){
                    $success = true;
                    $status_code = 200;
                    $response_message = $message;
                }
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('ProjectsController::save_project_category() error');
        }

        return Response::json([
            'success' => $success,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }

    public function get_project_category_details($category_id) :JsonResponse
    {
        Log::info("ProjectsController::get_project_category_details() Start");

        $success = false;
        $status_code = 500;
        $data = [];
        $response_message = "something went wrong please try again.";

        try {
            $data = ProjectCategoryModel::where('id',$category_id)->get()->first();

            $success = true;
            $status_code = 200;
            $response_message ='';
        }catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical("ProjectsController::get_project_category_details() Error");
            Log::critical($e);
        }

        Log::info("ProjectsController::get_project_category_details() End");
        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }
    // Project Category End


    // Project Master Start
    public function projects_page(): View
    {
        return view('project.project_list');
    }

    public function project_details($project_id = false): View
    {
        $data['ProjectCategory'] = ProjectCategoryModel::pluck('category','id');
        $data['Skills'] = SkillsMasterModel::pluck('name','name');

        if(!empty($project_id)){
            $project_info = ProjectsModel::where('id',$project_id)->first();
            $project_info['image'] = Helpers::firebase_img_url($project_info['image']);
            $data['project_info'] = $project_info;
        }

        return view('project.project_details',$data);
    }

    public function projects_list(Request $request):JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $query = ProjectsModel::join('project_category','project_category.id','projects.project_category_id')
                ->select(['projects.id as project_id','project_category.category','projects.title','projects.image','projects.started','projects.ended','projects.language_used','projects.description','projects.github','projects.webapp','projects.created_at']);

            $data['result'] = $query->orderBy('projects.id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('image', function ($row) {
                        $image = Helpers::firebase_img_url($row->image);
                        return '<img src="'.$image.'" width="70">';
                    })
                    ->addColumn('language_used', function ($row) {
                        $lanuage = explode(',',$row->language_used);
                        $html ='<ul>';

                        foreach ($lanuage as $ls){
                            $html.="<li>⚬ $ls</li>";
                        }
                        $html .='</ul>';

                        return $html;
                    })
                    ->addColumn('github', function ($row) {
                        return ($row->github != '') ? '<a class="btn-social github" href="'.$row->github.'" target="_blank"><i class="fab fa-github"></i></a>' : '-';
                    })
                    ->addColumn('live_link', function ($row) {
                        return ($row->webapp != '') ? '<a class="btn-social facebook" href="'.$row->webapp.'" target="_blank"><i class="fa fa-link"></i></a>' : '-';
                    })
                    ->addColumn('created_at', function ($row) {
                        return date('d M, Y h:i A', strtotime($row->created_at));
                    })
                    ->addColumn('description', function ($row) {
                        return "<p>$row->description</p>";
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="d-flex">
                                        <a href="'.route('project_details',[$row->project_id]).'" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="'.route('delete_project',[$row->project_id]).'" class="btn btn-danger shadow btn-xs sharp delete_project" data-project-id="'.$row->project_id.'"><i class="fa fa-trash"></i></a>
                                    </div>';
                    })
                    ->rawColumns(['image','created_at','action','created_by','live_link','github','language_used','description'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('ProjectsController::skills_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_projects(Request $request):JsonResponse
    {
        $sucess = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'image'             => isset($request->project_id) && !empty($request->project_id) ? '' : 'required',
                'title'             => 'required',
                'project_category'  => 'required|exists:project_category,id',
                'started'           => 'required',
                'language_used'     => 'required',
                'description'       => 'required',
            ];

            $messages = [
                'language_used.required' => 'Please Enter the Language Used',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                $data = [
                    'project_category_id'   => $post['project_category'],
                    'title'                 => $post['title'],
                    'started'               => $post['started'],
                    'ended'                 => @$post['ended'] ?? '',
                    'description'           => $post['description'],
                    'language_used'         => implode(',',$post['language_used']),
                    'webapp'                => @$post['live'] ?? '',
                    'github'                => @$post['github'] ?? '',
                ];

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $data['image'] = Helpers::save_img_firebase('Project',$image,$post['title']);
                }

                $message = "Project Saved Sucessfully";

                if(isset($request->project_id) && !empty($request->project_id)){
                    $category = ProjectsModel::find($request->project_id);
                    $category->update($data);
                    $message = "Project Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $category = ProjectsModel::create($data);
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
            Log::critical('ProjectsController::save_projects() error');
        }

        return Response::json([
            'success' => $sucess,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }

    public function delete_project($skill_id): JsonResponse
    {
        $Delete_data = ProjectsModel::find($skill_id);
        Helpers::delete_firebase_img($Delete_data->image);
        $Delete_data->delete();

        Log::info('ProjectsController::delete_project() isset($Delete_data)');
        if (isset($Delete_data)) {
            Log::info("true\n\n");

            return response()->json(['status' => true, 'message' => 'Skill Removed successfully']);
        } else {
            Log::info("false\n\n");

            return response()->json(['status' => false, 'message' => 'Something went wrong']);
        }
    }
    // Project Master End
}
