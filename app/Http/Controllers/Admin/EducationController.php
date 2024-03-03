<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EducationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EducationController extends Controller
{
    public function education_page() : View
    {
        return view('education.education_page');
    }

    public function education_details($education_id = false) : View
    {
        $data = [];
        if(!empty($education_id)) {
            $education_data = EducationModel::find($education_id);
            $education_data->logo = Helpers::firebase_img_url($education_data->logo);
            $data['education_data'] = $education_data;
        }
        return view('education.education_details',$data);
    }

    public function education_list(Request $request): JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $data['result'] = EducationModel::orderBy('id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('logo', function ($row) {
                        $logo = '';
                        if(!empty($row->logo)){
                            $path = Helpers::firebase_img_url($row->logo);
                            $logo = '<img src="'.($path).'" width="70">';
                        }
                        return $logo;
                    })
                    ->addColumn('started', function ($row) {
                        return !empty($row->started) ? date('M Y',strtotime($row->started)): '';
                    })
                    ->addColumn('ended', function ($row) {
                        return !empty($row->ended) ? date('M Y',strtotime($row->ended)): '';
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="d-flex">
                                        <a href="/education-details/'.$row->id.'" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp delete_education" data-education-delete="/delete-education/'.$row->id.'"><i class="fa fa-trash"></i></a>
                                    </div>';
                    })
                    ->rawColumns(['logo','started','action','created_by'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('EducationController::education_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_education(Request $request): JsonResponse
    {
        $success = false;
        $status_code = 200;
        $response_message = 'Something went wrong! Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'logo'           => isset($request->education_id) && !empty($request->education_id) ? '' : 'required|mimes:jpeg,png,jpg,gif',
                'institute_name' => 'required',
                'degree'         => 'required',
                'started'        => 'required',
                'grade'          => 'required',
                'description'    => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            } else {
                $user_id = Auth::id();

                $endMonth = date('M Y',strtotime($post['ended'])) ?? '';

                if(empty($post['ended'])){
                    $endMonth = 'Present';
                }

                $data = [
                    'institute_name' => $post['institute_name'],
                    'degree'         => $post['degree'],
                    'started'        => date('M Y',strtotime($post['started'])),
                    'ended'          => $endMonth,
                    'grade'          => $post['grade'],
                    'description'    => $post['description'],
                    'created_by'     => $user_id,
                ];

                if ($request->hasFile('logo')) {
                    $original_name = $request->logo->getClientOriginalName();
                    $image = $request->file('logo');
                    $data['logo'] = Helpers::save_img_firebase('Education',$image,$original_name);
                }

                $message = "Education Saved Successfully";

                if(isset($request->education_id) && !empty($request->education_id)){
                    $education = EducationModel::find($request->education_id);
                    $education->update($data);
                    $message = "Education Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $education = EducationModel::create($data);
                }

                if ($education) {
                    $success = true;
                    $response_message = $message;
                }
            }
        } catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('EducationController::save_education() error');
        }

        return Response::json([
            'success'       => $success,
            'message'       => $response_message,
            'errors_fields' => $errors_fields,
        ], $status_code);
    }

    public function delete_education($id): JsonResponse
    {
        try {
            $education = EducationModel::find($id);
            if ($education) {

                Helpers::delete_firebase_img($education->logo);
                $education->delete();

                return response()->json(['success' => true, 'message' => 'Deleted successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Education not found']);
            }
        } catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::error('EducationController::delete_education() error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }
}
