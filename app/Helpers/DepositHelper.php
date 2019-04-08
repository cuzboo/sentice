<?php

namespace App\Helpers;

trait DepositHelper{

	private function sumMoney($money){
        $sum = 0;
        foreach ($money as $deposit) {
            $sum += $deposit->amount;
        }

        return $sum;
    }
}