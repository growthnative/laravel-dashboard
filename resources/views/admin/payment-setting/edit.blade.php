@extends('layouts.theme.admin.default')
@section('title', 'Payment Setting')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Update Payment Settings</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('payment-setting.index') }}">Manage Payment Setting</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Payment Setting</li>
                </ol>
            </nav>
    
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('payment-setting.update' , $paymentSetting->id) }}" id="payment-data">
                @csrf
                @method('PATCH')
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputStripeKey" placeholder="Enter your stripe key" class="form-control @error('stripe_key') is-invalid @enderror" name="stripe_key" required autocomplete="stripe_key" autofocus value="{{old('stripe_key', $paymentSetting->stripe_key)}}">
                                <label for="inputStripeKey">Stripe Key<span class="required-role">*</span></label>
                                @error('stripe_key')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputStripeToken" placeholder="Enter your stripe token" class="form-control @error('stripe_secret') is-invalid @enderror" name="stripe_secret" required autocomplete="stripe_secret" autofocus value="{{old('stripe_secret', $paymentSetting->stripe_secret)}}">
                                <label for="inputStripeToken">Stripe Token<span class="required-role">*</span></label>
                                @error('stripe_secret')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="url" id="inputWebhookUrl" placeholder="Enter your webhok url" class="form-control @error('webhook_url') is-invalid @enderror" name="webhook_url" autocomplete="webhook_url" autofocus value="{{old('stripe_secret', $paymentSetting->webhook_url)}}"  pattern="https://.+" title="Requires https://"  required />
                                <label for="inputWebhookUrl">URL<span class="required-role">*</span></label>
                                @error('webhook_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 mb-0 pro-sub">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Update') }}
                    </button>
                    <a href="{{ route('payment-setting.index') }}" class="btn btn-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection