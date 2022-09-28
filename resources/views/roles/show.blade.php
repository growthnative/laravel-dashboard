@extends('layouts.theme.admin.default')
@section('title', 'Users')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Show Roles</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Manage Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Show Roles</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $role->name }}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permissions:</strong>
                        <br/>
                        @php  
                            $roleName = [];
                            $permissionName = [];
                        @endphp

                        @foreach($permissionData as $k=>$permission)
                            @foreach($permission as $key=>$value)
                                @php 
                                    $groupName = $value->group_name;
                                    $permissionTypeId = $value->permission_type_id;
                                    $permissionNameValue = $value->name;
                                @endphp

                                @if(!in_array($groupName, $roleName))
                                    @php
                                        $roleName[$permissionTypeId] = $groupName;
                                       
                                    @endphp
                                @endif

                                @if(!in_array($permissionNameValue, $permissionName))
                                    @php
                                        $permissionName[] = $permissionNameValue;
                                    @endphp
                                @endif
                            @endforeach    
                        @endforeach

                        @foreach($roleName as $key=>$permission)
                     
                        @php 
                            $permissionName = (new \App\Helpers\helpers)->getPermissionName($key , $id); 
                            $perName = implode(',' , $permissionName);
                        @endphp

                        <label for="permission">{{ $permission }}  
                        <!-- {{ $perName}} -->
                    
                        </label><br>
                        
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection