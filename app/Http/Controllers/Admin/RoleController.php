<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Config;
use App\Models\PermissionType;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    { 
        $this->middleware('permission:role-read|role-write|role-edit|role-delete', ['only' => ['index','show','create', 'store','edit' , 'update', 'delete']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //Pagination count from config file
        $items_per_page = Config::get('global.pagination.items_per_page');

        // if logged-in user is ADmin
        // if(Auth::user()->id == 1)
        // {  
        //     // Get roles data from database(if role is admin)
        //     $roles = Role::orderBy('id','DESC')->paginate($items_per_page);

        // }else{
            // Get roles data whose role is rather than admin
            $roles = Role::orderBy('id','DESC')->where('created_by' , Auth::user()->id)->paginate($items_per_page);
        // }

        // Redirect to view
        return view('roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get permission data from database
        $permission = PermissionType::orderBy('id','DESC')->with('permission')->get();
       
        // Redirect to view
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get selected permission data
        $permissionArr = $request->permission;
    
        // Validation for role
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        // Create new role
        $rolename = $request->input('name');

        $existData = Role::where('created_by' , Auth::user()->id)->where('name',ucfirst($rolename))->exists();

        if($existData == true){
            return redirect()->back()->withInput()->with("error","Rolename should not be same");
        } else{
            // Make first characted upercase
            $role = Role::create(['name' => ucfirst($rolename), 'created_by' => Auth::user()->id]);
        }

        // $role->syncPermissions($request->input('permission'));
        
        // Check if data exist on Permission type table or not
        if($role){
            // Get last inserted id
            $lastInserteredId = $role->id;

            foreach($permissionArr as $permissioniId){
              
               // Save data on Permission has role table
                DB::table('role_has_permissions')->insertGetId(array(
                    'permission_id' => $permissioniId,
                    'role_id'       => $lastInserteredId,
                ));
            }
        }
        else{
            return redirect()->back()->with("error", "Opps! Something went wrong");
        }
    
        // Redirect to route
        return redirect()->route('roles.index')->with('success','Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find id of Role whose data need to show
        $role = Role::find($id);
        
        // Get permission data
        $permission = Role::leftJoin('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
        ->leftJoin('permissions','permissions.id' , '=' , 'role_has_permissions.permission_id')
        ->leftJoin('permission_types','permission_types.id','=','permissions.permission_type_id')
        ->where('roles.id' , $id)
        ->select('permission_types.group_name', 'permissions.*')
        ->get();
        
        $permissionData = $permission->groupBy('permission_type_id');
       
        // Redirect to view
        return view('roles.show',compact('role','permissionData' , 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get id which need to update
        $role = Role::find($id);

        // Get permission data
        $permission = PermissionType::orderBy('id','DESC')->with('permission')->get();
        
        // Get permission assign to differnet role
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
            
        // Redirect to view
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation for role
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'. $id,
            'permission' => 'required',
        ],
        // custom message for image
        [
            'permission.required' => 'Please choose atleast one permission'
        ]);

        // Get requested permission type's id
        $permissionArr = $request->permission;
        
        // check if permission name already exist or not
        $checkAlreadyExist = Role::where('name' , trim($request['name']))->where('id','!=',  $id)->count();

        //If Permission name already exist then display error 
        if($checkAlreadyExist > 0){
            return back()->withInput()->with('error','This Role is already exists');
        }
    
        $input = $request->all();

        // Get role id
        $role = Role::find($id);

        $role->update($input);

        // Save data on database
        // $role->save();

        // Get permission data as per permission type id
        $permissionTypeRole = DB::table("role_has_permissions")->where('role_id' , $id)->pluck('permission_id')->toArray();
        
        if(count($permissionTypeRole) > 0){
            // Get difference 
            $deletedPermissionId = array_merge(array_diff($permissionArr, $permissionTypeRole), array_diff($permissionTypeRole, $permissionArr));

            // Delete data if some permission is removed
            if(count($deletedPermissionId) > 0){
               
                DB::table("role_has_permissions")->whereIn('permission_id' , $deletedPermissionId)->where('role_id', $id)->delete();
            }
        }

        if(is_array($permissionArr) && count($permissionArr) > 0){
          
                foreach($permissionArr as $pid){
               
                    $permissionData =  DB::table("role_has_permissions")->where('permission_id' , $pid)->where('role_id' , $id)->first();
                    $lastInserteredId = $role->id;
                    // save data on role_has_permission table
                    if(empty($permissionData)){
                       
                        DB::table('role_has_permissions')->insertGetId(array(
                            'permission_id'   => $pid,
                            'role_id'        => $lastInserteredId,
                        ));
                    }
                }
    
            }
            else{
                // Return error to select atleast one checkbox
                return redirect()->back()->with("error", "Opps! Something went wrong");//Return back
            }
       
        return redirect()->route('roles.index')->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get id from role table which need to be deleted
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')->with('success','Role deleted successfully');
    }

}
