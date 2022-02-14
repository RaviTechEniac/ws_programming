<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public $successStatus = 'Successful';
    public $failStatus = 'Failed';

    public function register(Request $request)
    {
        $validate = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password'
        ];

        $validator = Validator::make($request->all(), $validate);

        if($validator->fails()){

            $message = $validator->messages();
            return response()->json(['success' => false, 'code'=>$this->failStatus, 'message'=>$message],500);
        }

        $inputs = $request->all();

        $user = User::create([
            'name' => $inputs['name'],
            'password' => bcrypt($inputs['password']),
            'email' => $inputs['email']
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        $message ="New user added successful!";
        return response()->json(['success' => true,'data' => $user, 'code'=>$this->successStatus, 'message'=>$message,'access_token' =>  $token]);
    }

    public function login(Request $request)
    {
        $validate = [
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ];

        $validator = Validator::make($request->all(), $validate);

        if($validator->fails()){

            $message = $validator->messages();
            return response()->json(['success' => false, 'code'=>$this->failStatus, 'message'=>$message],500);
        }

        $inputs = $request->all();

        if (!Auth::attempt($inputs)) {
            $message = 'Credentials not match';
            return response()->json(['success' => false, 'code'=>$this->failStatus, 'message'=>$message]);
        }

        $token = auth()->user()->createToken('API Token')->plainTextToken;
        $user = auth()->user();
        $message = 'You are successfully logged in';
        return response()->json(['success' => true,'data' => $user, 'code'=>$this->successStatus, 'message'=>$message,'access_token' =>  $token]);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        $message = 'Tokens revoked!';
        return response()->json(['success' => true,'code'=>$this->successStatus, 'message'=>$message]);
    }
}
