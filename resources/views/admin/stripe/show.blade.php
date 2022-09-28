@extends('layouts.theme.admin.default')
@section('title', 'Payment Details')

@section('content')

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Show Payment Details</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{  route('stripeDetails') }}">Manage Payment</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payment Details</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name :</strong>
                        {{ ucfirst($paymentDetails->card_name) }} 
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Card Number :</strong>
                        {{ $paymentDetails->card_number }} 
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Transaction Id :</strong>
                        {{ $paymentDetails->transaction_id }} 
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Total Amount :</strong>
                        {{ $paymentDetails->amount }} 
                    </div>
                </div>

                @php 
                    $paymentAmount = (new \App\Helpers\helpers)->getPaymentDetails($paymentDetails->id); 

                    $remainingAmount =  $paymentDetails->amount - $paymentAmount[0]->amount;
                @endphp

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Refunded Amount :</strong>
                        @if($paymentAmount[0]->amount)
                            {{ $paymentAmount[0]->amount }}
                        @else
                            0.00
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Remaining Amount :</strong>
                        {{ number_format($remainingAmount,2) }}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Currency :</strong>
                        {{ $paymentDetails->currency }} 
                    </div>
                </div>
                @php  
                    $year =  $paymentDetails->exp_year;
                    $year = substr( $year, -2);

                    $date = $paymentDetails->created_at;
                    $date = date('Y-m-d', strtotime($date));
                @endphp
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Expire Date :</strong>
                        {{ $paymentDetails->exp_month }}/{{$year}}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Payment Method :</strong>
                        {{ ucfirst($paymentDetails->payment_method) }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Payment Status :</strong>
                        {{ ucfirst($paymentDetails->status) }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Payment Date :</strong>
                        {{ $date }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection