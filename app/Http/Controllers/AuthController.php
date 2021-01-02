<?php

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Exceptions\Handler;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;




class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }
    public $loginAfterSignUp = true;

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), 
                [ 
                    'username' => 'required|string|between:2,25|unique:users',
                    'email' => 'required|string|email:rfc,dns|max:100|unique:users',
                    'password' => 'required|string|between:6,25',
                    'password_confirmation' => 'required|same:password',
                    'phone_number'=>'required|unique:users',
                    'first_name'=>'required|string|between:2,10',
                    'last_name'=>'required|string|between:2,10',
                    'address'=>'required|string|between:5,50',
                ]);  
 
            if ($validator->fails()) {  
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_BAD_REQUEST); 
            }
            $user = DB::table('users')->insertGetID(
                [
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone_number' => $request->phone_number,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address
                ]
            );
            if ($this->loginAfterSignUp) {
                return $this->login($request);
            }
    
            return response()->json([
                'success'   =>  true,
                'data'      =>  $user
            ], Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }        
        
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
    */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
