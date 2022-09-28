<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use App\Models\PermissionType;
use Spatie\Permission\Models\Permission; 
use App\Models\PaymentDetails;

class Helpers{

    /** 
     * This helper function is responsible to get permission name
     *
     * @return void
    */
    public static function getPermissionName($id , $role_id)
    {
        $permissionName = Permission::where('permission_type_id' , $id)->get();
        $parray = array();
        if(!empty($permissionName)){
            
            foreach($permissionName as $pid)
            {
                $pName = $pid->name;                
                
                $rolePermissionId =  DB::table('role_has_permissions')->where('permission_id' , $pid->id)->where('role_id' , $role_id)->count();
               
                if($rolePermissionId > 0)
                {
                    $parray[] = $pName;
                }
            }          
        }
        // Return data
        return $parray;
    }

    public static function getPaymentDetails($id)
    {
       
        $paymentAmountData = PaymentDetails::leftJoin('payment_refunds', 'payment_refunds.payment_id', '=', 'payment_details.id')
        ->where('payment_refunds.payment_id' , $id)
        ->selectRaw("SUM(payment_refunds.refund_amount) as amount") 
        ->get();

        return $paymentAmountData;
    }
}
?>