<?php

namespace App;


use App\Helpers\DepositHelper;
use App\Models\{Deposit, Bonus};
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, DepositHelper;
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'country', 'gender', 'bonus', 'email', 'password',
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

    public function deposits(){

        return $this->hasMany(Deposit::class, 'user_id', 'id');
    }

    public function bonuses(){
        
        return $this->hasMany(Bonus::class, 'user_id', 'id');
    }

    public function getCurrentStateAttribute(){
       $deposits = $this->deposits;
        if($deposits->isEmpty()){
            return 0;
        }
        $moneyAdded = $deposits->where('type', self::DEPOSIT);
        $moneySpent = $deposits->where('type', self::WITHDRAW);
        $added = $this->sumMoney($moneyAdded);
        $spent = $this->sumMoney($moneySpent);

        return $added - $spent;
    }
}
