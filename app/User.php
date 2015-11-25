<?php

namespace App;

use App\Sms\SmsCourierInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    public function sms()
    {
        return $this->messages();
    }

    // 挑戰 4：如果我不想使用 Event 系統來發簡訊的話怎麼辦？
    // Solution 1: 把傳送簡訊的責任放在 User Model 裡，而不是 SendSMS handle 裡，
    //             就不會被 event 綁住，拿到 User 就可以傳簡訊。
    public function sendSms(SmsCourierInterface $courier, $to, $message)
    {
        $courier->sendTextMessage([
            'to'      => $to,
            'message' => $message
        ]);

        $this->sms()->create([
            'to'      => $to,
            'message' => $message
        ]);
    }
}
