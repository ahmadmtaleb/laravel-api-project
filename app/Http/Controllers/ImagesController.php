<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Items;
use App\Models\Images;
use App\Models\User;
use Exception;
use JWTAuth;
use Validator;

class ImagesController extends Controller
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
            $result = DB::table('images')->get()->toArray();
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
            $result = DB::table('images')
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
                    'file' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                    ]);  
 
            if ($validator->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_INTERNAL_SERVER_ERROR); 
    
            }
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $file_name = 'item-'.time().'.'.$image->getClientOriginalExtension();
                $file_path = $image->storeAs('items', $file_name);
                $image = DB::table('images')->insertGetID(
                    [
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'item_id' => $request->item_id
                    ]
                );
                if ($image) {
                    return response()->json([
                        'success' => true,
                    ], Response::HTTP_OK);
                }
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
            // $image = Images::find($id);
            // $searched_item_id = $image->item_id;
            // $item = Items::find($searched_item_id);

            // if(!Gate::allows('image-owner', $item, $image)) {
            //     return response()->json([
            //             'success' => false,
            //     ], Response::HTTP_FORBIDDEN);
            // }
            $validator = Validator::make($request->all(), 
                [ 
                    'file' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                ]);  
 
            if ($validator->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_UNAUTHORIZED); 
    
            }
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $file_name = 'item-'.time().'.'.$image->getClientOriginalExtension();
                $file_path = $image->storeAs('items', $file_name);
                $updated = DB::table('images')
                            ->where('id', '=', $id)
                            ->update(
                            [
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                            ]);
                if ($updated) {
                    return response()->json([
                        'success' => true,
                    ], Response::HTTP_OK);
                }
            }            
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }     
    }

    // /**
    //  * @param $id
    //  * @return \Illuminate\Http\JsonResponse
    // */
    public function destroy($id)
    {
        try{
            $deleted = DB::table('images')
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
