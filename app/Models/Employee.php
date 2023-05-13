<?php

namespace App\Models;

use EmployeeResetPasswordNotificationController;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Support\Facades\DB;

class Employee extends Model implements CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;
    use HasFactory;

    protected $casts = [
        'born_date' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'email',
        'born_date',
        'phone',
        'city',
        'address',
        'casual_days',
        'seasonal_days',
        'type',
        'company_id',
        'is_active'
    ];


    public function getEmailForPasswordReset()
    {
        $email = DB::table('users')
            ->select('email')
            ->where('id', '=', $this->user_id)
            ->first();

        return $email;
    }

    public function sendNewPasswordResetNotification($token)
    {
        $this->notify(new EmployeeResetPasswordNotificationController($token));
    }
}
