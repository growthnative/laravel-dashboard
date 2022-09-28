@extends('layouts.theme.admin.default')
@section('title', 'Users')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Update Role</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Manage Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Role</li>
                </ol>
            </nav>
            <div class="input-error col-md-6">
                <span>If you update role it will impact throughout the system</span>
            </div>
           
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('roles.update' , $role->id) }}" id="role-data">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-5">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputRoleName" placeholder="Enter Role Name" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" autofocus value="{{old('name', $role->name)}}">
                                <label for="inputRoleName">Role Name<span class="required-role">*</span></label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-7">
                        <div class="form-group">
                            <strong>Permission:</strong>
                            <br/>
                            @foreach($permission as $value)
                                <label for="permission-{{$value->id}}"><strong>{{ $value->group_name }} </strong></label>
                        
                                @foreach($value->permission as $permissionName)
                                    @php
                                        $is_checked='';
                                        $permissionArr = $permissionName['id'];
                                    
                                        if(in_array($permissionArr,$rolePermissions)){
                                            $is_checked='checked';
                                        }
                                    @endphp   
                                    <input type="checkbox" class="permission-name" id="permission-{{ $value->id }}" name="permission[]" value="{{ $permissionName->id }}" {{$is_checked}} ><br>
                                    <!-- <label for="permission-{{$permissionName->name}}">{{ $permissionName->name }}</label><br> -->
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 mb-0 ">
                        <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update role?');">
                            {{ __('Update') }}
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection