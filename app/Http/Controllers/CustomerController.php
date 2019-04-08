<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Faker\Generator as Faker;
use App\Helpers\DepositHelper;
use App\Models\{Deposit, Bonus};
use App\Jobs\{AddMoney, WithdrawMoney};
use Illuminate\Database\Eloquent\Collection;

class CustomerController extends Controller
{
	use DepositHelper;

    public function index(){

    	return view('welcome');
    }

    public function editUser(Faker $faker){
    	try {
			$users = User::all();
			$userToEdit = $users[rand(0, count($users)-1)];
		    $freshUser = factory(User::class)->make()->toArray();
			$userToEdit->update($freshUser);

			return response()->json([
				    "message" => "User with id of {$userToEdit->id} has been updated",
				    "status" => 200
				]);
    	} catch (\Exception $e) {

    		return response()->json([
    			    "message" => "Problems occured updating the user with the id of {$userToEdit->id}.",
    			    "status" => 500
    			]);
    	}
    }
   
    public function report($date=null){
    	if(!$date){
    		$date = Carbon::now()->sub(7, 'days');
    	}else{
    		$regex = "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/";
    		if(!preg_match($regex, $date)){
    			return response()->json([
    			    "message" => "Date must be in format 'YYYY-MM-DD'",
    			    "status" => 403
    			]);
    		}
    		$date = Carbon::parse($date);
    	}

    	$deposits = Deposit::whereDate('created_at', '>', $date)->get();    	
		$deposits = $this->organizeDeposits($deposits);

		return view('report', compact('deposits'));
    }

    private function organizeDeposits(Collection $deposits){
    	$deposits = $deposits->groupBy(function($deposit){
    			return $deposit->created_at->format('Y-m-d');
    	});

    	foreach ($deposits as $country => $transfers) {
    		$transfers = $transfers->groupBy(function($deposit){

    		return $deposit->customer->country;
    		});

    		$transfers = $this->organizeTrasfers($transfers);

    		$deposits[$country] = $transfers;
    	}

    	return $deposits;
    }

    private function organizeTrasfers(Collection $transfersCollection){
    	$usefulData = [];
    	foreach ($transfersCollection as $date => $transfers) {
			$usefulData['unique_customers'] = $transfers->groupBy('user_id')->count();
			$deposits = $transfers->where('type', 'deposit');
			$withdraws = $transfers->where('type', 'withdraw');
			
			$usefulData['no_of_deposits'] = $deposits->count();
			$usefulData['total_deposit_amount'] = $this->sumMoney($deposits);
			$usefulData['no_of_withdraws'] = $withdraws->count();
			$usefulData['total_withdraws_amount'] = -$this->sumMoney($withdraws);

			$transfersCollection[$date] = $usefulData;
    	}

    	return $transfersCollection;
    }

    public function addMoney($id, $amount){

    	if(!is_numeric($amount)){

    		return response()->json([
			    "message" => "Quantity must be numeric.",
			    "status" => 403
			]);
    	}

        $user = User::findOrFail($id);
    	$job = new AddMoney($user, $amount);
    	$this->dispatch($job);

		return response()->json([
            "message" => "{$user->first_name} {$user->last_name} has added {$amount}.",
            "status" => 200
        ]);
    }

    public function withdrawMoney($id, $amount){

    	if(!is_numeric($amount)){

    		return response()->json([
			    "message" => "Quantity must be numeric.",
			    "status" => 403
			]);
    	}

    	$user = User::findOrFail($id);

    	if(($user->currentState - $amount)<0){

    		return response()->json([
    			"message" => "You can not withdraw the money. Your current state is {$user->currentState}, and you want to withdraw {$amount}",
    			'status' => 403
    		]);
    	}

    	$job = new WithdrawMoney($user, $amount);
    	$this->dispatch($job);

    	return response()->json([
            "message" => "{$user->first_name} {$user->last_name} has withdrawn {$amount}.",
            "status" => 200
        ]); 
    }  
}