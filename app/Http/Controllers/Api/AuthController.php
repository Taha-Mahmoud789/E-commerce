<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Requests\AdminRegisterAuthRequest;

class AuthController extends Controller
{
    use GeneralTrait;
    // public function __construct()
    // {
    //     // Apply jwt.auth middleware to protected routes only
    //     $this->middleware('jwt.auth', ['except' => ['register', 'login', 'adminRegister', 'adminLogin']]);
    // }
    // Register new user
    public function register(RegisterAuthRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Ensure this field exists in your users table
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    // Authenticate user and return token
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->returnData('token', $token, "Success Login");
    }
    public function adminRegister(AdminRegisterAuthRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Ensure this field exists in your users table
        ]);

        if ($user) {
            return $this->adminLogin($request);
        }
        return response()->json(["msg" => " Somthing Wrong"]);
    }

    public function adminLogin(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator  = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator, $code);
            }

            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('api')->attempt($credentials);

            if (!$token) {
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');
            }

            $user = Auth::guard('api')->user();
            $user->token = $token;
            //return token
            return $this->returnData('user', $user);
        } catch (\Exception $e) {
            $this->returnError($e->getCode(), $e->getMessage());
        }
        return $this->returnError("", "No Data Found");
    }
    // Log Out 
    public function logout(Request $request)
    {

        try {
            // Get the token from the request
            $token = JWTAuth::getToken();
            // Invalidate the token
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $exception) {
            return response()->json(['error' => 'Failed to log out, please try again'], 500);
        }
    }
    // Get authenticated user details
    public function getUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(compact('user'));
    }
}
