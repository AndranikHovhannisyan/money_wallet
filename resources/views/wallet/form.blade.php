@extends('layouts.app')

@section('content')
    @php $wallet = $wallet ?? null @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('wallet.index') }}">Wallets</a> -> {{$wallet ? "Edit " . $wallet->name : "Create " }}
                Wallet
                <div class="card">
                    <div class="card-header">{{ __($wallet ? 'Edit Wallet' : 'Create Wallet') }}</div>

                    <div class="card-body">
                        <form method="POST"
                              action="{{ $wallet ? route('wallet.update', ['wallet' => $wallet->id]) : route('wallet.store') }}">
                            @csrf
                            @if($wallet)
                                @method('PUT')
                            @endif
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ $wallet ? $wallet->name : "" }}" required autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                                <div class="col-md-6">
                                    <input id="type" type="text"
                                           class="form-control @error('type') is-invalid @enderror" name="type"
                                           value="{{ $wallet ? $wallet->type : "" }}" required>

                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
