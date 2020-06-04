<form method="POST" action="{{ route('record.store', $wallet->id) }}">
    @csrf

    <div class="form-group row">
        <div class="form-group col-md-5">
            <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" required autofocus placeholder="{{ __('Amount') }}">

            @error('amount')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        <div class="form-check col-md-1">
            <input id="isCredit" type="checkbox" class="form-check-input @error('isCredit') is-invalid @enderror" name="isCredit">

            @error('isCredit')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <label class="form-check-label" for="isCredit">{{ __('IsCredit') }}</label>
        </div>


        <div class="form-group col-md-2">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>
        </div>
    </div>
</form>
