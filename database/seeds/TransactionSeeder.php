<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Transactions;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user)
        {
            for ($i = 0; $i <= 15; $i++)
            {
                $user->transactions()
                    ->save(
                        new Transactions([
                            'type'  =>  ($i % 2) ? 'debit' : 'credit',
                            'amount'    =>  rand(1, 1000)
                        ])
                    );
            }
        }
    }
}
