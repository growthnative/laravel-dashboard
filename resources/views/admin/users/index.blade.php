@extends('layouts.theme.admin.default')
@section('title', 'Users')

@section('content')

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-lg-12">
			<div class="mt-4">
				<h3 class="font-weight-light">Manage Users</h3>
			</div>

			@include('flash-message')
			<div class="alert alert-success alert-block d-none ajax-msg"> 
				<strong id="successMessage"></strong>
        	</div>
			
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
					<li class="breadcrumb-item active" aria-current="page">Manage Users</li>
				</ol>
			</nav>

			<div class="col-md-12">
				<div class="top-form">
				<form action="{{ route('users.index') }}"  method="GET" id="formUserListing">
					<div class="row">
						<div class="form-group col-md-4 mb-2">
							<input type="text" class="form-control" placeholder="Search Name.." name="name" value="{{request()->get('name')}}" >
						</div>
						<div class="form-group col-md-4 mb-2">
							<input type="text" class="form-control" placeholder="Search Email.." name="email" value="{{request()->get('email')}}" >
						</div>
						<div class="col-md-12">
							<div class="col-md-4">
								<button type="submit" id="userSearch" class="btn btn-primary custom-btn-admin float-left">Search</button>	
							</div>	
						</div>
					</div>
				</form>
				<div class="reset-button">
					<a href="{{ route('users.index') }} " class="custom-btn-admin btn btn-primary user-reset-btn" onclick="return confirm('Do you really want to reset filters ?')">
						Reset
					</a>
				</div>
			</div>
		</div>
			
				<div class="pull-user mb-2">
					<a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
				</div>
			
			<table class="table table-bordered">
				<tr>
					<th>No</th>
					<th>Name</th>
					<th>Email</th>
					<th>Roles</th>
					<th width="280px">Action</th>
				</tr>
				@if($data->count() > 0)

					@foreach ($data as $key => $user)
						<tr>
							<td>{{ ++$i }}</td>
							<td>{{ ucfirst($user->first_name)}} {{ $user->last_name}}</td>
							<td>{{ $user->email }}</td>
							<td>
								@if(isset($user->getRole->name) && (!empty($user->getRole->name)))
									{{ $user->getRole->name}} 
									@else
									n/a
								@endif
							</td>

							<td class="icon-ad">
								<input type="text" value="{{ csrf_token() }}" style="display:none;">
								@if(($user->email_verified_at != '') && ($user->password != ''))
									<a data-current="{{$user['status']}}" data-url="{{ url('updateStatus ')}}" data-id="{{$user['id']}}" href="javascript:void(0)" id="textchange" class="userStatusUpdate">
										@if($user['status'] == 'Active')
										<label class="switch">
											<input class="user_status" type="checkbox" checked>
											<span class="slider round"></span>
										</label>
										@else
										<label class="switch">
											<input class="user_status" type="checkbox">
											<span class="slider round"></span>
										</label>
										@endif
									</a>
								@endif
								
									<a class="show-data" href="{{ route('users.show', $user->id) }}">
										<i class="fa fa-eye" aria-hidden="true" title="Show"></i>
									</a>
								
									<a class="show-data" href="{{ route('users.edit',$user->id) }}">
										<i class="fa fa-edit" aria-hidden="true" title="Edit"></i>
									</a>
								
									<form action="{{ route('users.destroy' , $user->id) }}" method="POST">
										@csrf
										@method('DELETE')
										<button type="submit" class="delete-data" onclick="return confirm('Are you sure you want to delete this item?');">
											<i class="fa fa-trash" aria-hidden="true" title="Delete"></i>
										</button>
									</form>
								
								@if($user->email_verified_at == '' || $user->password == null)
									<a class="resend" href="{{ route('resendEmail', $user->id) }}">Resend Email link</a>
								@endif
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
				{{ $data->links() }}
			</div>
		</div>
	</div>
</div>

@endsection