<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Items;
use Exception;
use JWTAuth;
use Validator;


class ItemsController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @return mixed
    */
    public function index()
    {
        try{
            $result = DB::table('items')->get()->toArray();
            if($result){
                return response()->json([
                    'success' => true,
                    'data' => $result
                ], Response::HTTP_OK);
            }
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
    */
    public function show($id)
    {
        try{
            $result = DB::table('items')
                    ->select('*')
                    ->where('id', '=', $id)
                    ->get();
            if($result){
                return response()->json([
                    'success' => true,
                    'data' => $result[0]
                ], Response::HTTP_OK);
            }
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
    */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), 
                      [ 
                      'name' => 'required|string|max:150',
                      'description' => 'required|string|between:100,500',
                      'price' => 'required|numeric',  
                      'quantity' => 'required|integer', 
                      'user_id' => 'required|in:'.\Auth::user()->id,
                     ]);  
 
            if ($validator->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_UNAUTHORIZED); 
    
            }
            $item = DB::table('items')->insertGetID(
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'user_id' => $request->user_id
                ]
            );
            if ($item) {
                return response()->json([
                    'success' => true,
                ], Response::HTTP_OK);
            }
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
    */
    public function update(Request $request, $id)
    {
        try{
            $item = Items::find($id);
            if(!Gate::allows('item-owner', $item)) {
                return response()->json([
                        'success' => false,
                    ], Response::HTTP_FORBIDDEN);
            }
            $validator = Validator::make($request->all(), 
                      [ 
                      'name' => 'string|max:150',
                      'description' => 'string|between:100,500',
                      'price' => 'numeric',  
                      'quantity' => 'integer', 
                      'user_id' => 'in:'.\Auth::user()->id,
                     ]);  
 
            if ($validator->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_UNAUTHORIZED); 
    
            }
            // $request->dd();
            $updated = DB::table('items')
              ->where('id', '=', $id)
              ->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity
              ]);
            if ($updated) {
                return response()->json([
                    'success' => true,
                ], Response::HTTP_OK);
            }
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }     
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
    */
    public function destroy($id)
    {
        try{
            $item = Items::find($id);
            if(!Gate::allows('item-owner', $item)) {
                return response()->json([
                        'success' => false,
                    ], Response::HTTP_FORBIDDEN);
            }
            $deleted = DB::table('items')
              ->where('id', '=', $id)
              ->delete();
            if ($deleted) {
                return response()->json([
                    'success' => true,
                ], Response::HTTP_OK);
            }
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
