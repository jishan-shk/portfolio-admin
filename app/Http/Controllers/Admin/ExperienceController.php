<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ExperienceDocumentModel;
use App\Models\ExperienceModel;
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

class ExperienceController extends Controller
{
    public function experience_page() :View
    {
        return view('experience.experience_page');
    }

    public function experience_details($experience_id = false) :View
    {
        $data['skills'] = SkillsMasterModel::pluck('name','name');
        if(!empty($experience_id)) {
            $experience_data = ExperienceModel::find($experience_id);
            if (!empty($experience_data)) {
                $data['experience_data'] = ExperienceModel::find($experience_id);
                $data['document'] = ExperienceDocumentModel::select(['file_name', 'id as document_id'])->where('experience_id', $experience_id)->get();
            }
        }

        return view('experience.experience_details',$data);
    }

    public function experience_list(Request $request):JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 500;
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "";

            $data['result'] = ExperienceModel::orderBy('id','desc')->get();

            if ($request->ajax()) {
                return DataTables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('company_logo', function ($row) {
                        $logo = '';
                        if(!empty($row->company_logo)) {
                            $path = Helpers::firebase_img_url($row->company_logo);
                            $logo = '<img src="' . ($path) . '" width="70">';
                        }

                        return $logo;
                    })
                    ->addColumn('documents', function ($row) {
                        $documents = ExperienceDocumentModel::where('experience_id',$row->id)->exists();
                        return $documents ? '<button class="btn btn-info view_document" data-experience-doc-route="/get-company-document/'.$row->id.'"><i class="fa fa-eye"></i></button>' : '-';
                    })
                    ->addColumn('action', function ($row) {
                        return '<div class="d-flex">
                                        <a href="/experience-details/'.$row->id.'" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="/experience/delete-experience/'.$row->id.'" class="btn btn-danger shadow btn-xs sharp delete_experience" data-experience-id="'.$row->id.'"><i class="fa fa-trash"></i></a>
                                    </div>';
                    })
                    ->rawColumns(['company_logo','documents','action','created_by'])
                    ->make(true);
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('ExperienceController::experience_list() error');
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
        ], $status_code);
    }

    public function save_experience(Request $request):JsonResponse
    {
        $sucess = false;
        $status_code = 200;
        $response_message = 'Something went wrong!Please try again later.';
        $errors_fields = '';

        try {
            $post = $request->all();

            $rules = [
                'company_name'        => isset($request->experience_id) && !empty($request->experience_id) ? 'required|unique:experience,company_name,'.$request->experience_id : 'required|unique:experience,company_name',
                'role'                => 'required',
                'start_date'          => 'required',
                'company_logo'        => isset($request->experience_id) && !empty($request->experience_id) ? '' : 'required|mimes:jpeg,png,jpg,gif',
                'skills'              => 'required|array',
            ];

            $messages = [
                'company_logo.required' => 'Company logo is required',
                'company_name.unique' => 'Company Experience Already Exist',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $response_message = "Validation Error";
                $status_code = 400;
                $errors_fields = $validator->errors();
            }else{
                $user_id = Auth::id();

                $endMonth = date('M Y',strtotime($post['end_date'])) ?? '';

                if(empty($post['end_date'])){
                    $endMonth = 'Present';
                }

                $data = [
                    'company_name'    => $post['company_name'],
                    'role'            => ucwords($post['role']),
                    'description'     => $post['description'],
                    'start'           => date('M Y',strtotime($post['start_date'])),
                    'end'             => $endMonth,
                    'end_status'      => $post['end_date'] ?? '',
                    'skills'          => implode(', ',$post['skills']),
                ];

                if ($request->hasFile('company_logo')) {
                    $image = $request->file('company_logo');
                    $data['company_logo'] = Helpers::save_img_firebase('Logo',$image,$post['company_name']);
                }

                $message = "Experience Saved Sucessfully";

                if(isset($request->experience_id) && !empty($request->experience_id)){
                    $experience = ExperienceModel::find($request->experience_id);
                    $experience->update($data);
                    $message = "Experience Successfully Updated";
                }else{
                    $data['created_by'] = Auth::id();
                    $experience = ExperienceModel::create($data);
                }

                if($experience){
                    if ($request->hasFile('document')) {
                        foreach ($request->file('document') as $document){
                            $original_name = $document->getClientOriginalName();
                            $document_name = Helpers::save_img_firebase('Logo',$document,$original_name);

                            ExperienceDocumentModel::create(['experience_id' => $experience->id ,'file_name' => $document_name,'created_by' => $user_id]);
                        }
                    }

                    $sucess = true;
                    $status_code = 200;
                    $response_message = $message;
                }
            }
        }catch(\Exception $e){
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical($e);
            Log::critical('ExperienceController::save_experience() error');
        }

        return Response::json([
            'success' => $sucess,
            'message' => $response_message,
            'errors_fields' => $errors_fields,
        ],$status_code);
    }

    public function delete_experience($experience_id): JsonResponse
    {
        Log::info('ExperienceController::delete_experience() start');
        $Delete_data = ExperienceModel::find($experience_id);

        Helpers::delete_firebase_img($Delete_data->company_logo);

        $Delete_data->delete();

        Log::info('ExperienceController::delete_experience() isset($Delete_data)');
        if (isset($Delete_data)) {
            $exp_doc = ExperienceDocumentModel::where('experience_id',$experience_id)->get();
            foreach ($exp_doc as $experience){
                Helpers::delete_firebase_img($experience['file_name']);
            }
            ExperienceDocumentModel::where('experience_id',$experience_id)->delete();

            Log::info("true\n\n");
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        } else {
            Log::info("false\n\n");
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function delete_exp_document($document_id): JsonResponse
    {
        Log::info('ExperienceController::delete_exp_document() start');
        $Delete_data = ExperienceDocumentModel::find($document_id);

        Helpers::delete_firebase_img($Delete_data->file_name);
        $Delete_data->delete();

        Log::info('ExperienceController::delete_exp_document() isset($Delete_data)');
        if (isset($Delete_data)) {
            Log::info("true\n\n");
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        } else {
            Log::info("false\n\n");
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function getCompanyDocument(Request $request,$experience_id): JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 422;
        $errors_fields = [];
        $response_message = "something went wrong please try again.";

        try {
            $success = true;
            $status_code = 200;
            $response_message = "Record Found";

            $data['result'] = ExperienceDocumentModel::where('experience_id', $experience_id)->get();

            if ($request->ajax()) {
                return Datatables::of($data['result'])
                    ->addIndexColumn()
                    ->addColumn('file_path', function ($row) {
                        return Helpers::firebase_img_url($row->file_name);
                    })
                    ->rawColumns(['file_path'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical("Enrollment::getCompanyDocument");
            Log::critical($e);
        }

        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
            'errors_fields' => $errors_fields,
        ], $status_code);
    }
}
