@extends('layouts.theme.admin.default')
@section('title', 'Users')

@section('content')

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Manage Roles</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Roles</li>
                </ol>
            </nav>

            <div class="pull-user mb-2">
                <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
            </div>
            
            @include('flash-message')

            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
                @php
                    $i=0;
                @endphp
               
                @if($roles->count() > 0)
                    @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td> 
                        
                        <td>
                            <a class="show-data" href="{{ route('roles.show',$role->id) }}">
                                <i class="fa fa-eye" aria-hidden="true" title="View"></i>
                            </a>
                           
                            @if($role['id'] != '1')
                                <a class="edit-role-data" href="{{ route('roles.edit',$role->id) }}">
                                    <i class="fa fa-edit" aria-hidden="true" title="Edit" ></i>
                                </a>
                            @endif
                        </td>
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

{!! $roles->render() !!}

@endsection