<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 * @OA\Schema(schema="User",
 *                           required={"name"},
 *                           required={"email"},
 *                           required={"password"})
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;

     /**
     * @OA\Property()
     * @var string
     */
    public $name;

    /**
     * @var string
     * @OA\Property()
     */
    public $email;

    /**
     * @var string
     * @OA\Property()
     */
    public $created_at;

    /**
     * @var string
     * @OA\Property()
     */
    public $updated_at;
   

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $table = "users";

    public static function rules()
    {
        return [
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:32',
            'name' => 'required|max:32'
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
   public function getJWTIdentifier()
   {
       return $this->getKey();
   }
 
   /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
   public function getJWTCustomClaims()
   {
       return [];
   }
 
   public function setPasswordAttribute($password)
   {
       if ( !empty($password) ) {
           $this->attributes['password'] = bcrypt($password);
       }
   }
  
}
