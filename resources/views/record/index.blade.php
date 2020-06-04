@extends('layouts.app')

@section('content')

    <div class="container">
        <a href="{{ route('wallet.index') }}">Wallets</a> -> "{{ $wallet->name }}" Wallet Records

        <div>
            Wallet Name: <strong>{{ $wallet->name }}</strong><br />
            Wallet Type: <strong>{{ $wallet->type }}</strong><br />
        </div>

        <div class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Type</th>
                </tr>
                </thead>
                <tbody>

                    @php $total = 0 @endphp
                    @foreach ($records as $record)
                        <tr>
                            <th>{{ $record->id }}</th>
                            <td>{{ $record->amount }}</td>
                            <td>{{ $record->isCredit ? "Credit" : "Debit" }}</td>
                        </tr>

                        @php $total += $record->amount * ($record->isCredit ? 1 : -1) @endphp

                    @endforeach

                </tbody>
            </table>
        </div>
        <div>
            Total: {{ $total }}
        </div>

        <div class="card-body">
            @include('record.form')
        </div>

    </div>

@endsection
