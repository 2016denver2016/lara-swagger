<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
 
    /** 
     * @OA\Post(
     *      path="/api/login",
     *      operationId="Auth user",
     *      tags={"Auth"},
     *      summary="Login",
     *      description="Returns project data",
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="email",
     *                   description="User name",
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
     *          description="successful operation",
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
     *                               "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU4MzQwMjMwOCwiZXhwIjoxNTgzNDA1OTA4LCJuYmYiOjE1ODM0MDIzMDgsImp0aSI6Imt6NnJBTmxCNkNuZ2g4VjUiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ._Fu_Rh3VVIOBShTfcPe54Ok-dch9yu0BQMNHEbpDXvc",
     *                               "token_type": "apiKey",
     *                               "expires_in": 3600
     *                       }
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(response=401, description="Login failed")    
     * )
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="Logout user",
     *      tags={"Auth"},
     *      summary="Logout",
     *      description="User Logout",
     *      security={
     *                 {"JWT_Request": {}}
     *               },
     *    @OA\Response(
     *          response=401,
     *          description="logout successful",
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
     *                               {
     *                                   "status": "Unauthorized"
     *                               }
     *                       }
     *                 )
     *             )
     *         }
     *    )
     * )
     */


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->guard = "api";
    }
   /**
    * Get a JWT via given credentials.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function login()
   {
       $credentials = request(['email', 'password']);
      
    try {
        if (!$token = auth()->attempt($credentials)) {

           return response()->json(['error' => 'Unauthorized'], 401);
        }
    } catch (JWTException $e) {
            
        return response()->json(['error' => 'could_not_create_token'], 500);
    }
        $user = User::first();

        $token = JWTAuth::fromUser($user);
       
        return response()->json([
           'access_token' => $token,
           'token_type' => 'apiKey',
           'expires_in' => auth($this->guard)->factory()->getTTL() * 60
       ]);
       
       
        
 
    //    return $this->respondWithToken($token);
    
   }
 
   /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function me()
   {
       return response()->json(auth()->user());
   }
 
   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       auth($this->guard)->logout();
 
       return response()->json(['message' => 'Successfully logged out']);
   }
 
   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh()
   {
       return $this->respondWithToken(auth()->refresh());
   }
 
  
   
}