<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Transactions;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'user'  =>  Auth::user(),
            'users' =>  User::paginate(10)
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactionShow()
    {
        return view('transaction', [
            'transactions'  => Auth::user()
                ->transactions()
                ->get()
        ]);
    }

    /**
     * @param TransactionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function transactionPost(TransactionRequest $request)
    {
        Auth::user()->transactions()
            ->save(
                new Transactions($request->all())
            );

        return redirect('transactions');
    }
}
