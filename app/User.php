<?php

namespace App;

use App\Mail\NewUserWelcomeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username','email', 'password',
    ];

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

//    BOOT *****************************************************
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($user){
            $user->profile()->create([
                'title' => $user->name,
            ]);

//       Send Mail By This **************************************
            Mail::to($user->email)->send(new NewUserWelcomeMail());
        });
    }

// For Post **************************************************
    public function posts()
    {
        // Order By ******************************************************
        return $this->hasMany(Post::class)->orderBy('created_at','DESC');
    }

//    Following **************************************************
    public function following(){
        return $this->belongsToMany(Profile::class);
    }

// For Profile ###############################################
    public function profile(){

            return $this->hasOne(Profile::class);
        }

}
