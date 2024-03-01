<?php

namespace App\Http\Controllers;

use App\Models\ErrorLogModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ErrorLogController extends Controller
{
    public function error_logs_page(): View
    {
        return view('error.error_log');
    }

    public function error_logs_list(): JsonResponse
    {
        $data['result'] = DB::table('error_logs')
            ->select(['id', 'url', 'line', 'code', 'file', 'message', 'created_at'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return Datatables::of($data['result'])
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return date('d M, Y h:i A', strtotime($row->created_at));
            })
            ->addColumn('action', function ($row) {
                return '<button type="button" data-delete-id ="' . $row->id . '"  class="delete_log btn btn-danger btn-sm" >Delete</button>';
            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    public function delete_error_logs_list(Request $request): JsonResponse
    {
        Log::info('ErrorLogController::delete_error_logs_list() start');

        if ($request->delete_id == 'all') {
            ErrorLogModel::truncate();
        } else {
            ErrorLogModel::where('id', $request->delete_id)->delete();
        }

        Log::info('ErrorLogController::delete_error_logs_list() end');
        return Response::json(array(
            'success' => true,
            'message' => 'Deleted successfully',
        ), 200);

    }
}
