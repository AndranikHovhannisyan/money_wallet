@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                @php $total = 0 @endphp
                @foreach ($wallets as $wallet)
                    <tr>
                        <th>{{ $wallet->id }}</th>
                        <td>{{ $wallet->name }}</td>
                        <td>{{ $wallet->type }}</td>
                        <td>{{ $wallet->amount }}</td>
                        <td>
                            <a href="{{ route('wallet.edit', $wallet->id) }}">Edit</a>
                            <a href="{{ route('wallet.destroy', $wallet->id) }}">Delete</a>
                            <a href="{{ route('record.index', $wallet->id) }}">Records</a>
                        </td>
                    </tr>

                    @php $total += $wallet->amount  @endphp

                @endforeach

                </tbody>
            </table>
        </div>
        <div>
            Total: {{ $total }}
            <a class="btn btn-primary" href="{{ route('wallet.create') }}">Add Wallet</a>
        </div>
    </div>

@endsection
