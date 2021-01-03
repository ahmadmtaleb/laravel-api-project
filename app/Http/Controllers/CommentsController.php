<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\Comments;
use Exception;
use JWTAuth;
use Validator;

class CommentsController extends Controller
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
            $result = DB::table('comments')->get()->toArray();
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
            $result = DB::table('comments')
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
            $validator0 = Validator::make($request->all(), 
                    [
                    'comments_type_id' => 'required',
                    'item_id' => 'required',
                    'user_id' => 'required|in:'.\Auth::user()->id,
                    'file' => 'nullable',
                    'text' => 'nullable'
                    ]);
            if ($validator0->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message0'=>$validator0->errors()
                ], Response::HTTP_INTERNAL_SERVER_ERROR); 
            }
            if($request->comments_type_id === 1){ //text
                $validator1 = Validator::make($request->all(), 
                    [
                    'text' => 'required|string|max:1000',
                    ]);  
 
                if ($validator1->fails()) {  
                    return response()->json([
                        'success' => false,
                        'message1'=>$validator1->errors()
                    ], Response::HTTP_INTERNAL_SERVER_ERROR); 
                }
                $comment = DB::table('comments')->insertGetID(
                    [
                        'text' => $request->text,
                        'item_id' => $request->item_id,
                        'user_id' => $request->user_id,
                        'comments_type_id' => $request->comments_type_id,
                        // 'file_path' => null,
                    ]
                );
                if ($comment) {
                    return response()->json([
                        'success' => true,
                    ], Response::HTTP_OK);
                }
            }
            if ($request->hasFile('file')) {
                if($request->comments_type_id === 2){ //image 
                    $validator2 = Validator::make($request->all(), 
                    [
                        'file' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                    ]);  
                    if ($validator2->fails()) {  
                        return response()->json([
                            'success' => false,
                            'message2'=>$validator2->errors()
                        ], Response::HTTP_INTERNAL_SERVER_ERROR); 
                    }
                    $image = $request->file('file');
                    $file_name = 'comment-'.time().'.'.$image->getClientOriginalExtension();
                    $file_path = $image->storeAs('comments', $file_name);
                    $comment = DB::table('comments')->insertGetID(
                        [
                            'file_path' => $file_path,
                            'item_id' => $request->item_id,
                            'user_id' => $request->user_id,
                            'comments_type_id' => $request->comments_type_id,
                            // 'text' => null,
                        ]
                    );
                    if ($comment) {
                        return response()->json([
                            'success' => true,
                        ], Response::HTTP_OK);
                    }
                }
                if($request->comments_type_id === 3){ //audio
                    $validator3 = Validator::make($request->all(), 
                    [
                        'file' => 'required|audio|mimes:mpga,mp2,mp2a,mp3,m2a,m3a,wma,ram,m4a|max:2048',
                    ]);  
                    if ($validator3->fails()) {  
                        return response()->json([
                            'success' => false,
                            'message3'=>$validator3->errors()
                        ], Response::HTTP_INTERNAL_SERVER_ERROR); 
                    }
                    $audio = $request->file('file');
                    $file_name = 'comment-'.time().'.'.$audio->getClientOriginalExtension();
                    $file_path = $audio->storeAs('comments', $file_name);
                    $comment = DB::table('comments')->insertGetID(
                        [
                            'file_path' => $file_path,
                            'item_id' => $request->item_id,
                            'user_id' => $request->user_id,
                            'comments_type_id' => $request->comments_type_id,
                            // 'text' => null,
                        ]
                    );
                    if ($comment) {
                        return response()->json([
                            'success' => true,
                        ], Response::HTTP_OK);
                    }
                }
                if($request->comments_type_id === 4){ //video
                    $validator4 = Validator::make($request->all(), 
                    [
                        'file' => 'required|video|mimes:mimes:3gp,mp4,mp4v,mpg4,mpeg,mpg,mpe,m1v,m2v|max:2048',
                    ]);  
                    if ($validator4->fails()) {  
                        return response()->json([
                            'success' => false,
                            'message4'=>$validator4->errors()
                        ], Response::HTTP_INTERNAL_SERVER_ERROR); 
                    }
                    $video = $request->file('file');
                    $file_name = 'comment-'.time().'.'.$video->getClientOriginalExtension();
                    $file_path = $video->storeAs('comments', $file_name);
                    $comment = DB::table('comments')->insertGetID(
                        [
                            'file_path' => $file_path,
                            'item_id' => $request->item_id,
                            'user_id' => $request->user_id,
                            'comments_type_id' => $request->comments_type_id,
                            // 'text' => null,
                        ]
                    );
                    if ($comment) {
                        return response()->json([
                            'success' => true,
                        ], Response::HTTP_OK);
                    }
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
            $comment = Comments::find($id);
            if(!Gate::allows('comment-owner', $comment)) {
                return response()->json([
                        'success' => false,
                ], Response::HTTP_FORBIDDEN);
            }
            $validator = Validator::make($request->all(), 
                [ 
                    'file' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                    'text' => 'nullable|string|max:1000',
                    'user_id' => 'in:'.\Auth::user()->id,

                ]);  
 
            if ($validator->fails()) {  
    
                return response()->json([
                    'success' => false,
                    'message'=>$validator->errors()
                ], Response::HTTP_UNAUTHORIZED); 
    
            }
            if ($request->hasFile('file')) {
                if($request->comments_type_id === 2){ //image 
                    $image = $request->file('file');
                    $file_name = 'comment-'.time().'.'.$image->getClientOriginalExtension();
                    $file_path = $image->storeAs('comments', $file_name);
                    $comment = DB::table('comments')
                            ->where('id', '=', $id)
                            ->update(
                            [
                                'file_path' => $file_path,
                                'item_id' => $request->item_id,
                                'user_id' => $request->user_id,
                                'comments_type_id' => $request->comments_type_id
                            ]
                    );
                    if ($comment) {
                        return response()->json([
                            'success' => true,
                        ], Response::HTTP_OK);
                    }
                }
                elseif($request->comments_type_id === 3){ //audio

                }
                else{ //video

                }
            }
            else{ //text
                $comment = DB::table('comments')
                        ->where('id', '=', $id)
                        ->update(
                        [
                            'text' => $request->text,
                            'item_id' => $request->item_id,
                            'user_id' => $request->user_id,
                            'comments_type_id' => $request->comments_type_id
                        ]
                );
                if ($comment) {
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
            $comment = Comments::find($id);
            if(!Gate::allows('comment-owner', $comment)) {
                return response()->json([
                        'success' => false,
                ], Response::HTTP_FORBIDDEN);
            }
            $deleted = DB::table('comments')
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
