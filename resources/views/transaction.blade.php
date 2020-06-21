@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transactions</div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <form method="post" action="/transactions">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="form-type">Type</label>
                                            <select class="form-control" id="form-type" name="type">
                                                <option value="debit">Debit</option>
                                                <option value="credit">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="amount">Type</label>
                                            <input type="number" value="0" class="form-control" min="0" name="amount">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->type}}</td>
                                    <td>{{$transaction->amount}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
