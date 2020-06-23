<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use App\Transactions;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    private $userData;
    private $transactionData;
    public static $ROUTE = '/transactions';

    protected function setUp(): void
    {
        parent::setUp();

        $password = Hash::make('password');
        $this->userData = [
            'name'  =>  'andriy',
            'email' =>  'andriy@email.com',
            'password'              =>  $password,
            'password_confirmation' =>  $password,
            'permissions'           =>  true
        ];

        $this->transactionData = [
            'type'      =>  'debit',
            'amount'    =>  rand(1, 1000)
        ];
    }

    /** @test */
    public function users_table_exist()
    {
        $this->assertTrue(
            Schema::hasTable('transactions')
        );
    }

    /** @test */
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('transactions', [
                'id',
                'user_id',
                'type',
                'amount',
                'deleted_at',
                'created_at',
                'updated_at'
            ])
        );
    }

    /** @test */
    public function a_type_of_transaction_is_required()
    {
        $user = User::create($this->userData);
        $this->be($user);

        unset($this->transactionData['type']);
        $response = $this->post(self::$ROUTE, $this->transactionData);
        $response->assertSessionHasErrors('type');
    }

    /** @test */
    public function an_amount_of_transaction_is_required()
    {
        $user = User::create($this->userData);
        $this->be($user);

        unset($this->transactionData['amount']);
        $response = $this->post(self::$ROUTE, $this->transactionData);
        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function an_amount_of_transaction_must_be_numeric()
    {
        $user = User::create($this->userData);
        $this->be($user);

        $this->transactionData['amount'] = Str::random(5);
        $response = $this->post(self::$ROUTE, $this->transactionData);
        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function a_transaction_can_create()
    {
        $user = User::create($this->userData);
        $this->be($user);

        $response = $this->post(self::$ROUTE, $this->transactionData);
        $response->assertStatus(RedirectResponse::HTTP_FOUND);
        $this->assertCount(1, Transactions::all());
    }

    /** @test */
    public function user_do_not_have_permission()
    {
        $this->userData['permissions'] = false; // this user is not admin.
        $user = User::create($this->userData);
        $this->be($user);

        $response = $this->post(self::$ROUTE, $this->transactionData);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertCount(0, Transactions::all());
    }

    /** @test */
    public function a_user_have_transactions()
    {
        $user = User::create($this->userData);
        $user->transactions()
            ->save(
                new Transactions($this->transactionData)
            );

        $this->assertInstanceOf(
            Transactions::class,
            User::first()
                ->transactions()
                ->first()
        );
    }

    /** @test */
    public function a_transactions_belongs_to_a_user()
    {
        $user = User::create($this->userData);
        $user->transactions()
            ->save(
                new Transactions($this->transactionData)
            );

        $this->assertInstanceOf(
            User::class,
            Transactions::first()
                ->user()
                ->first()
        );
    }

    /** @test */
    public function a_user_get_sum_attribute_and_a_transaction_scope_debit()
    {
        $debitAmount = rand(1, 1000);
        $user = User::create($this->userData);
        $user->transactions()
            ->saveMany([
                new Transactions([
                    'type'      =>  'debit',
                    'amount'    =>  $debitAmount
                ]),
                new Transactions([
                    'type'      =>  'credit',
                    'amount'    =>  rand(1, 1000)
                ])
            ]);
        $this->assertEquals($debitAmount, User::first()->sum);
    }
}
