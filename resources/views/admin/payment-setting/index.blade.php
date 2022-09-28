@extends('layouts.theme.admin.default')
@section('title', 'Payment Setting')

@section('content')

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-lg-12">
			<div class="mt-4">
				<h3 class="font-weight-light">Manage Payment Settings</h3>
			</div>

			@include('flash-message')
			<div class="alert alert-success alert-block d-none ajax-msg"> 
				<strong id="successMessage"></strong>
        	</div>
			
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
					<li class="breadcrumb-item active" aria-current="page">Payment Settings</li>
				</ol>
			</nav>
			
            <div class="pull-user mb-2">
                <a class="btn btn-success" href="{{ route('payment-setting.create') }}"> Create Payment Setting</a>
            </div>
			
			<table class="table table-bordered" >
				<tr>
					<th>#</th>
					<th>Stripe Key</th>
                    <th>Stripe Token</th>
					<th>Webhook Url</th>
					<th class="action">Action</th>
				</tr>
				@php
                    $i=0;
                @endphp

				@if($paymentSettings->count() > 0)

					@foreach($paymentSettings as $paymentSetting)
						<tr class="payment-setting">
							<td>{{ ++$i }}</td>
							<td>{{ $paymentSetting->stripe_key }}</td>
							<td>{{ $paymentSetting->stripe_secret }}</td>
							<td style="width: 220px;">{{ $paymentSetting->webhook_url }}</td>

							<td class="icon-ad">
								<input type="text" value="{{ csrf_token() }}" style="display:none;">
								<a data-current="{{ $paymentSetting->status}}" data-url="{{ url('updatePaymentStatus')}}" data-id="{{ $paymentSetting['id'] }}" href="javascript:void(0)" id="textchange" class="paymentStatusUpdate">
									@if($paymentSetting['status'] == 1)
										<label class="switch">
											<input class="payment_status" type="checkbox" checked>
											<span class="slider round"></span>
										</label>
									@else
										<label class="switch">
											<input class="payment_status" type="checkbox">
											<span class="slider round"></span>
										</label>
									@endif
								</a>
								<a class="show-data" href="{{ route('payment-setting.edit', $paymentSetting->id) }}">
									<i class="fa fa-edit" aria-hidden="true" title="Edit"></i>
								</a>
							</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td class="text-center" colspan="5">No Data Found</td>
					</tr>	
				@endif
            </table>
            <div class="pagination-custom">
				{{ $paymentSettings->links() }}
			</div>
		</div>
	</div>

   
</div>

@endsection