<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login_page()
    {
        if (Auth::check()) return redirect()->route('dashboard');

        return view('auth.login');
    }

    public function user_login(Request $request) :JsonResponse
    {
        $data = [];
        $success = false;
        $status_code = 200;
        $errors_fields = [];
        $response_message = "something went wrong please try again.";

        try {
            $rules = [
                'username'  => 'required|exists:users,username',
                'password'  => 'required',
            ];

            $messages = [
                'username.required' => 'User Name is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $status_code = 400;
                $response_message = "Validation Error";
                $errors_fields = $validator->errors();
            } else {

                $userDetails = User::where('username', $request->username)->first();

                $isLoginFailed = true;
                if ($userDetails) {
                    if (Hash::check($request->password, $userDetails->password)) {
                        Auth::login(User::find($userDetails->id));

                        if (Auth::check()) {
                            $success = true;

                            $status_code = 200;
                            $data['redirectRoute'] = route('dashboard');
                            $response_message = "Login Successfully";
                            $isLoginFailed = false;
                        }
                    }
                }

                if ($isLoginFailed) {
                    $status_code = 400;

                    $response_message = "Invalid Password Enter";
                    $errors_fields = [
                        "password" => "Invalid Password Enter",
                    ];
                }
            }
        } catch (\Exception $e) {
            $status_code = 500;
            Helpers::save_error_log($e->getMessage(),$e->getLine(), $e->getFile(),true);

            Log::critical("LoginController::user_login");
            Log::critical($e);
        }


        return Response::json([
            'success'       => $success,
            'data'          => $data,
            'message'       => $response_message,
            'errors_fields' => $errors_fields,
        ], $status_code);
    }

    public function logout()
    {
        Auth::logout();

        return Redirect('/');
    }

    public function check_connection(){
        try {
            $result = User::all();

            echo "Database connected successfully!";
        } catch (QueryException $e) {
            echo "Failed to connect to the database: " . $e->getMessage();
        }
    }
}
