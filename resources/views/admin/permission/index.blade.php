@extends('layouts.theme.admin.default')
@section('title', 'Permission')

@section('content')

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-lg-12">
			<div class="mt-4">
				<h3 class="font-weight-light">Manage Permission</h3>
			</div>

			@include('flash-message')
			
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
					<li class="breadcrumb-item active" aria-current="page">Manage Permission</li>
				</ol>
			</nav>

            <!-- <div class="pull-user mb-2">
                <a class="btn btn-success" href="{{ route('permission.create') }}"> Create Permission</a>
            </div> -->
           
			<table class="table table-bordered">
				<tr>
					<th>No</th>
					<th>Name</th>
                    <th>Permission</th>
					<!-- <th width="280px">Action</th>  -->
				</tr>

                @php
                    $i=0;
                @endphp
                
				@if($permissions->count() > 0)
                    @foreach ($permissions as $key => $permission)
            
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $permission->group_name }}</td> 
                        <td>
                            @php
                                $string ='';
                            @endphp
                            @if(isset($permission->permission) && (!empty($permission->permission)))
							    @foreach($permission->permission as $value)	
                                @php $string .= ",$value->name" @endphp
                                    
                                @endforeach
                               @php $string = substr($string,1)
                               @endphp
                               {{ $string }}
							@else
								n/a
							@endif
                        </td>
                        <!-- <td>
                            <a class="show-data" href="{{ route('permission.edit',$permission->id) }}">
                                <i class="fa fa-edit" aria-hidden="true" title="Edit"></i>
                            </a>  
                        </td> -->
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5">No Data Found</td>
                    </tr>
                @endif
			</table>
			
		</div>
	</div>
</div>

@endsection