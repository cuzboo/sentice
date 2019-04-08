<?php

namespace App\Jobs;
use App\User;
use Illuminate\Bus\Queueable;
use App\Helpers\DepositHelper;
use App\Models\{Deposit, Bonus};
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, DepositHelper;
    public $tries = 5;
    protected $user;
    protected $amount;
    const DEPOSIT = 'deposit';
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       try {
            $deposits = $this->user->deposits()->where('type', 'deposit')->get();
            if(count($deposits)%3===0){
                $this->addBonus($this->user, $deposits);
            }
            $deposit = new Deposit();
            $deposit->user_id = $this->user->id;
            $deposit->amount = $this->amount;
            $deposit->type = self::DEPOSIT;
            $deposit->save();
            
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    private function addBonus($user, $deposits){
        try {
            $sum = $this->sumMoney($deposits);
            $bonus = $sum * $user->bonus/100;

            $newBonus = new Bonus();
            $newBonus->user_id = $user->id;
            $newBonus->amount = $bonus;
            $newBonus->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
