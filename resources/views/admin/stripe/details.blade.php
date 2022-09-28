@extends('layouts.theme.admin.default')
@section('title', 'Payment')

@section('content')

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-lg-12">
			<div class="mt-4">
				<h3 class="font-weight-light">Manage Payment</h3>
			</div>

			@include('flash-message')
			
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
					<li class="breadcrumb-item active" aria-current="page">Payment</li>
				</ol>
			</nav>
    
			<div class="col-md-12">
				<div class="top-form">
                    <form action="{{ route('stripeDetails') }}"  method="GET" id="paymentListing">
                        <div class="row">
                            <div class="form-group col-md-4 mb-2">
                                <input type="text" class="form-control" placeholder="Card Number.." name="card_number" value="{{request()->get('card_number')}}" >
                            </div>

                            <div class="form-group col-md-4 mb-2">
                                <input type="date" class="form-control" placeholder="Payment Date.." name="created_at" value="{{request()->get('created_at')}}" >
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <button type="submit" id="formPaymentListing" class="btn btn-primary custom-btn-admin float-left">Search</button>	
                                </div>	
                            </div>
                        </div>
                    </form>
                    <div class="reset-button">
                        <a href="{{ route('stripeDetails') }} " class="custom-btn-admin btn btn-primary user-reset-btn" onclick="return confirm('Do you really want to reset filters ?')">
                            Reset
                        </a>
                    </div>
			    </div>
		    </div>

            <div class="pull-user mb-2">
                <a class="btn btn-success" href="{{ route('paymentstripe') }}"> Create Payment</a>
            </div>
			
			<table class="table table-bordered" >
				<tr>
					<th>#</th>
					<th>Name</th>
                    <th>Transaction Id</th>
					<th>Amount</th>
                    <th>Refund Amount</th>
                    <th>Remaining Amount</th>
                    <th>Payment Status</th>
					<th class="action">Action</th>
				</tr>
				
                @php
                    $i=0;
                @endphp
                
                @if($paymentDetails->count() > 0)
                    @foreach ($paymentDetails as $payment)
                    @php 
                        $paymentAmount = (new \App\Helpers\helpers)->getPaymentDetails($payment->id); 
                        
                        $restAmount = $payment->amount - $paymentAmount[0]->amount;

                    @endphp
                    @php  
                        $year =  $payment->expire_year;
                        $year = substr( $year, -2);

                        $date = $payment->created_at;
                        $date = date('Y-m-d', strtotime($date));
                    @endphp
            
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ ucfirst($payment->card_name) }}</td> 
                        <td>{{ $payment->transaction_id }}</td>
                        <td>${{ $payment->amount }}</td>
                        @if(isset($paymentAmount[0]->amount) && (!empty($paymentAmount[0]->amount )))
                            <td>${{ $paymentAmount[0]->amount }}</td>
                        @else
                            <td>0.00</td>
                        @endif
                        <td>${{ number_format($restAmount,2) }}</td>
                        @php
                            $statusData = '';

                            if($payment->status == 2){
                                $statusData = 'succeeded';
                            } elseif($payment->status == 3){
                                $statusData = 'canceled';
                            } else{
                                $statusData = 'processing';
                            }
                        @endphp 
                        
                        
                        <td>{{ ucfirst($statusData) }}</td>
                        
                        <td>
                            <a class="show-data" href="{{ route('showPayment', $payment->id) }}">
								<i class="fa fa-eye" aria-hidden="true" title="View"></i>
							</a>
                            @if($restAmount > 0)
                                <a class="payment-show-data" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="data-pay" data-payment_id="{{ $payment->id}}" data-payment_amount="{{ $restAmount}}">
                                    Refund Amount
                                </a>   
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="10">No Data Found</td>
                    </tr>
                @endif
			</table>

            <div class="pagination-custom">
				{{ $paymentDetails->links() }}
			</div>
		</div>
	</div>

    <!-- Modal -->
    <form method="POST" action="{{ route('returnPayment') }}" id="permission-return">
        @csrf
        <input type="hidden" class="modalReset" id="payment_id" name="payment_id" value="">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Enter Refund Money</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="payment_amount" value="0">
                    <div class="modal-body">
                        <label class='control-label' id="amt-labl">Amount($)</label> 
                        <input class='form-control card-amount' name="amount" type='text' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"  id="amountData" data-max-amount="0" required>
                    </div>

                    <div id='divError'></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="payment_data" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection