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
        
            <form role="form" action="{{ route('make.payment') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                @csrf
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 form-data">
                            <div class="form-floating">
                                <div class='form-group required'>
                                    <label class='control-label'>Name on Card</label> <input
                                        class='form-control' name="card_name" type='text' required>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-12">
                        <div class="mb-3 form-data">
                            <div class="form-floating">
                                <div class='form-group required'>
                                    <label class='control-label'>Card Number</label> <input
                                        class='form-control card-number' type='text' required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 form-data">
                            <div class="form-floating">
                                <div class='form-group required'>
                                    <label class='control-label'>Amount</label> <input
                                        class='form-control card-amount' name="amount" type='text' required>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                    <label class='control-label'>CVC</label> <input autocomplete='off'
                                            class='form-control card-cvc' placeholder='ex. 311'
                                            type='text' required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                <label class='control-label'>Expiration Month</label> <input
                                            class='form-control card-expiry-month' name="expire_month" placeholder='MM' 
                                            type='text' required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class='form-row '>
                                <div class='form-group card required'>
                                <label class='control-label'>Expiration Year</label> <input
                                            class='form-control card-expiry-year' name="expire_year" placeholder='YYYY' 
                                            type='text' required>
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
                                
            </form>
        </div>
    </div>      
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
        if (response.error) {
            $('.error')
                .removeClass('d-none')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
     
});
</script>
@endsection