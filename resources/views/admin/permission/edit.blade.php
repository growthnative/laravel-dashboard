@extends('layouts.theme.admin.default')
@section('title', 'Permission')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Update Permission</h3>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Permission</li>
                </ol>
            </nav>
    
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('permission.update' , $permissionType->id) }}" id="permission-data-update">
                @csrf
                @method('PATCH')
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputPermissionGroupName" placeholder="Enter Permission name" class="form-control @error('group_name') is-invalid @enderror" name="group_name" required autocomplete="group_name" autofocus value="{{old('group_name', $permissionType->group_name)}}"  readonly>
                                <label for="inputPermissionGroupName">Permission Group Name<span class="required-role">*</span></label>
                                @error('group_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Select Permission </strong>
                            <br/>
                           
                            @foreach($permissionData as $key => $value )
                          
                            @php
                                $is_checked='';
                                $permissionArr = $key;
                                if(in_array($permissionArr,$permissionTypeData->toArray())){
                                    $is_checked='checked';
                                }
                            @endphp   
                                
                            <input type="checkbox" id="permission-{{ $key }}" name="permission[]" {{ $is_checked }} value="{{ $key }}">
                            <label for="permission-{{$key}}">{{ $value }}</label>
                        <br/>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 mb-0 ">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Update') }}
                    </button>
                    <a href="{{ route('permission.index') }}" class="btn btn-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection