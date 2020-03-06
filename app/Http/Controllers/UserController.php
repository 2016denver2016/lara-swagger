<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Validator;
 
use App\User;
  

     /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getProjectsList",
     *      tags={"Users"},
     *      summary="Get all users",
     *      description="Returns list of projects",
     *      security={
     *                 {"JWT_Request": {}}
     *               },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="errcode",
     *                         type="integer",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="errmsg",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                               "id": 1,
     *                               "name": "Denver",
     *                               "email": "shishkalovd@gmail.com",
     *                               "email_verified_at": null,
     *                               "created_at": "2020-03-02 14:25:10",
     *                               "updated_at": "2020-03-02 14:25:10"
     *                           
     *                     }
     *                 )
     *             )
     *         }
     *       ),    
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     * @OA\Get(
     *      path="/api/users/{id}",
     *      operationId="getProjectById",
     *      tags={"Users"},
     *      summary="Get user information",
     *      description="Returns project data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="User Not Found"),
     *      security={
     *                 {"JWT_Request": {}}
     *               }
     * )
     * @OA\Post(
     *      path="/api/users",
     *      operationId="User create",
     *      tags={"Users"},
     *      summary="Create User",
     *      description="Returns User data",
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="User name",
     *                   type="string"
     *                   
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="User login",
     *                   type="string"
     *                   
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   description="User password",
     *                   type="string"
     *                   
     *               ),
     *           )
     *       )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="User create successful",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="errcode",
     *                         type="integer",
     *                         description="The response code"
     *                     ),
     *                     @OA\Property(
     *                         property="errmsg",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                     example={
     *                               "id": 1,
     *                               "name": "Denver",
     *                               "email": "shishkalovd@gmail.com",
     *                               "email_verified_at": null,
     *                               "created_at": "2020-03-02 14:25:10",
     *                               "updated_at": "2020-03-02 14:25:10"
     *                       }
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(response=401, description="Create user failed"),
     *      security={
     *                 {"JWT_Request": {}}
     *               }    
     * )
     */
class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $users = User::all();
 
       return $users;
   }
 
   /**
    * Store a newly created resource in storage.
    *
    */
   public function store(Request $request)
   {
       $userData = $request->all();    

       $validator = Validator::make($userData, User::rules());
       var_dump($request->route()->uri());
       die();

        if ($validator->fails()) {
            return Response::json(array(
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                        'data' => array()
                            ), 422);
        } else {
            
            $user = User::create($userData);
        
            return $user;
        }
   }

   public function findUserById(Request $request)
   {
       $userId = $request->id;
       $user = User::find($userId);
       if(!$user){
           return Response::json(array(
                            'success' => false,
                            'errors' => 'User not found'
                                ), 404);

       }else{
           return $user;
       }
 
       
   }
}