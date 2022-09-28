@extends('layouts.theme.admin.default')
@section('title', 'Payment')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-8 payment">
            <div class="mt-4">
                <h3 class="font-weight-light title-payment">Payment</h3>
            </div>
    
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
        
            <form role="form" action="{{ route('make.payment') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key=
            "{{ env('STRIPE_KEY') }}" id="payment-form">
                @csrf

                @if($stripeSettingData->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 form-data">
                                <label class='control-label'>Please Select Your Stripe Secret</label> 
                                <div class="form-floating">
                                    <select name="custom_secret" class="form-control custom-select" id="payment-value">
                                        <option value="">Select Secret key </option>
                                        
                                        @foreach($stripeSettingData as $stripeSetting)
                                            <option value="{{ $stripeSetting->stripe_key }}" >{{ $stripeSetting->stripe_secret}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row payment-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 form-data">
                                <div class="form-floating">
                                    <div class='form-group required'>
                                        <label class='control-label'>Name on Card</label> 
                                        <input class='form-control' name="card_name" type='text' value="{{ old('card_name') }}" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-data">
                                <div class="form-floating">
                                    <div class='form-group required'>
                                        <label class='control-label'>Card Number</label> 
                                        <input class='form-control card-number' type='text' value="{{ old('card_number') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" minlength="16"  maxlength="16"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 form-data">
                                <div class="form-floating">
                                    <div class='form-group required'>
                                        <label class='control-label'>Amount($)</label> 
                                        <input class='form-control card-amount' name="amount" type='text' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');" minlength="1" value="{{ old('amount') }}" id="amountData" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                    <label class='control-label'>CVC</label> 
                                    <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' type='text' maxlength="3" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                    <label class='control-label'>Expiration Month</label> 
                                    <input class='form-control card-expiry-month' name="expire_month" placeholder='MM' type='text' value="{{ old('expire_month') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                    <label class='control-label'>Expiration Year</label> 
                                    <input class='form-control card-expiry-year' name="expire_year" placeholder='YYYY' type='text' value="{{ old('expire_year') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='form-row'>
                        <div class='col-md-12 error form-group d-none'>
                            <div class='alert-danger alert'>Please correct the errors and try
                                again.</div>
                        </div>
                    </div>
        
                    <div class="row pay-now">
                        <div class="col-sm-12">
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                        </div>
                    </div>     
                </div>        
            </form>  
        </div>
    </div>      
</div>

<div id="preloder">
    <div class="loader"></div>
</div>

@endsection


@section('script')
    
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
<script type="text/javascript">
  
$(function() {

    
    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    
    var $form = $(".require-validation");
     
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('d-none');
    
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('d-none');
                e.preventDefault();
            }
        });
     
        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            var checkData = $("#payment-value option:selected").val();
            if(checkData){
                $('#payment-form').attr('data-stripe-publishable-key',checkData);
            }
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                amount: $('card-amount').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
      
    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        // console.log(response);
        // return false;
        if (response.error) {
            $('.error')
                .removeClass('d-none')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
            console.log(token);
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
            $("#preloder").fadeIn();
        }
    }
     
});
</script>
@endsection

