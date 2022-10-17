<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
     
        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('admin-api')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');

            $admin = Auth::guard('admin-api')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('admin', $admin);

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

        public function logout (Request $request)
        {
             $token = $request ->auth_token;
            if($token)
            {
                try {
    
                    JWTAuth::setToken($token)->invalidate(); //logout
                  
                }
                catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                    return  $this -> returnError('','some thing went wrong');
                }
                return $this->returnSuccessMessage('Logged out successfully');
            }
            else{

               return $this -> returnError('','some thing went wrongs');
            }
          
        }

    
}
