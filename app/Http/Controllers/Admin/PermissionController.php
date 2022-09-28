<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermissionType;
use Spatie\Permission\Models\Permission;
use Config;
use DB;

/*
    |--------------------------------------------------------------------------
    | Change PasswordController
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling permission
    |
*/

class PermissionController extends Controller
{
    function __construct()
    { 
        //$this->middleware('permission:permission-read|permission-write|permission-edit|permission-delete', ['only' => ['index','show']]);
        // $this->middleware('permission:permission-write', ['only' => ['create','store']]);
        // $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        //Pagination count from config file
        $items_per_page = Config::get('global.pagination.items_per_page');

        $permissions = PermissionType::orderBy('id','DESC')->with('permission')->paginate($items_per_page);

        return view('admin.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Add data in array
        $permissionData  = [
            'read' => 'Read',
        ];
       
        // Redirect to view
        return view('admin.permission.create' , compact('permissionData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Get selected permission data
        $permissionArr = $request->permission;
        
        // Validation for role
        $this->validate($request, [
            'group_name' => 'required|unique:permission_types,group_name',
            'permission' => 'required',
        ]);

        // Get guard name from request
        $permissionName = $request->input('group_name');
      
        // Save data into database
        $permission = PermissionType::create(['group_name' => ucfirst($permissionName)]);

        $data =  $request->input();

        $name = $permission->group_name;

        $permissionName = str_replace(" ", "-", $name);
        $convertPermissionName = strtolower($permissionName);
        

        // Check if data exist on Permission type table or not
        if($permission){
            // Get last inserted id
            $lastInserteredId = $permission->id;
           
            foreach($permissionArr as $name){
                
                $convertName = strtolower($name);
                // Save data on Permission table
                $permission = new Permission;
                
                $permission->permission_type_id = $lastInserteredId;
                $permission->name =  $convertPermissionName. '-' .$convertName;
                $permission->guard_name = 'web';
                $permission->slug = $convertName;
				$permission->save();
            }
        }
        else{
            return redirect()->back()->with("error", "Opps! Something went wrong");
        }
        
        // Redirect to route
        return redirect()->route('permission.index')->with('success','Permission updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();
       
        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }

        // Add data in array
        $permissionData  = [
            'read' => 'Read',
        ];

        // Get id(which we need to edit)
        $permissionType = PermissionType::find($id);

        $permissionTypeData = Permission::where('permission_type_id' , $id)->pluck('slug');

        // Redirect to route
       return view('admin.permission.edit', compact('permissionType' , 'permissionData' , 'permissionTypeData'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // check weather user's active or not
        $checkUser = $this->checkUserStatus();

        //if user's status is not active then redirect on login 
        if($checkUser){
            return redirect()->route('login')->with('error' , 'Admin has not verified your account yet.');
        }
        
        // Validation for role
        $this->validate($request, [
            'group_name' => 'required|unique:permission_types,group_name,'. $id,
            'permission' => 'required',
        ]);

       
        // check if permission name already exist or not
        $checkAlreadyExist = PermissionType::where('group_name' , trim($request['group_name']))->where('id','!=',  $id)->count();

        //If Permission name already exist then display error 
        if($checkAlreadyExist > 0){
            return back()->withInput()->with('error','This Permission is already exists');
        }

        // Get id from permission-type table
        $permission = PermissionType::find($id);

        $name = $permission->group_name;

        $permissionName = str_replace(" ", "-", $name);

        $convertPermissionName = strtolower($permissionName);
            
        // Get requested permission type's id
        $permissionArr = $request->permission;

        foreach( $permissionArr as $permsissionKey => $permissionArrs ):
            $permissionArr[$permsissionKey] = $convertPermissionName.'-'.$permissionArrs;
        endforeach;

        // Get permission data as per permission type id
        $permissionId = Permission::where('permission_type_id' , $id)->pluck('name')->toArray();
       

        //Get difference 
        $deletedPermissionId = array_merge(array_diff($permissionArr, $permissionId), array_diff($permissionId, $permissionArr));
        
        //  Delete data if some permission is removed
        if(count($deletedPermissionId) > 0){
            Permission::whereIn('name' , $deletedPermissionId)->delete();
        }

        if(is_array($permissionArr) && count($permissionArr) > 0){
           foreach($permissionArr as $pname){
           
            $slug = explode("-",$pname);
           

            $convertName = strtolower($name);

            $permissionData = Permission::orderBy('id' , 'DESC')->where('name' , $pname)->first();
           
           
            // Create permisssion data
            if(empty($permissionData)){
                Permission::create([
                    'permission_type_id' => $id, 
                    'name' =>  $convertPermissionName. '-' .$slug[1],
                    'guard_name' =>'web',
                    'slug' =>  $slug[1]
                ]);	
            }
        }

        }
        else{
            // Return error to select atleast one checkbox
            return redirect()->back()->with("error", "Opps! Something went wrong");//Return back
        }
        
        // Redirect to route
        return redirect()->route('permission.index')->with('success','Permission updated successfully');
    }    
}
