<?php

namespace App\Jobs;

use App\User;
use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WithdrawMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;
    protected $user;
    protected $amount;
    const WITHDRAW = 'withdraw';
    
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
            $withdraw = new Deposit();
            $withdraw->user_id = $this->user->id;
            $withdraw->amount = $this->amount;
            $withdraw->type = self::WITHDRAW;
            $withdraw->save();

            return true;

        } catch (\Exception $e) {
            
            return response()->json([
                "message" => "Problem occured during withdrawing proccess.",
                "status" => 500
            ]);
        }
    }
}
