<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use Stripe;
use Config;

/*
    |--------------------------------------------------------------------------
    | Payment Setting Controller
    |--------------------------------------------------------------------------
    |
    | This Controller is helpful for handling payment setting detials (by managing stripe tokrn and stripe key)
*/
class PaymentSettingController extends Controller
{
    /**
     * This function is helpful for display payment's setting listing
     * *
     */
    public function index()
    {
        //Pagination count from config file
        $items_per_page = Config::get('global.pagination.items_per_page');

        $paymentSettings = PaymentSetting::paginate($items_per_page);
        return view('admin.payment-setting.index' , compact('paymentSettings'));
    }

    /**
     * This function is helpful for payment's setting form
     * *
    */
    public function create()
    {
        return view('admin.payment-setting.create');
    }

     /**
     * This function is helpful for store's data in database
     * *
     */
    public function store(Request $request)
    {
        // Validations while creating payment-setting form
        $this->validate($request, [
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
            'webhook_url' => 'required'
        ]);

        // $stripe = new \Stripe\StripeClient($request->stripe_secret);

        // $data = $stripe->accounts->all();

        // Get stripe details(thrugh key which we entered)
         $stripe = new \Stripe\StripeClient(
            $request->stripe_secret
        );

        // Url need to sync refund payment
        $syncPaymnetUrl = 'syncRefundPayment';

        // Create webhook (For refund payment)
        $data = $stripe->webhookEndpoints->create([
            'url' => $request->webhook_url.$syncPaymnetUrl,
            'enabled_events' => [
              'charge.refunded',
            ],
        ]);
       
        //Save payment-setting details in database
        $payment = PaymentSetting::create([
            'stripe_key' => $request->stripe_key,
            'stripe_secret' => $request->stripe_secret,
            'webhook_url' => $request->webhook_url,
            'api_response' => json_encode($data)
        ]);

        // Return back with error
        return redirect()->route('payment-setting.index')->with('success','Your Stripe Data Saved Succesfully');
    }

    /**
     * This function is helpful for edit data in database
     * *
     */
    public function edit($id)
    {
        // Get payment details as per the specific id
        $paymentSetting = PaymentSetting::find($id);
        
        return view('admin.payment-setting.edit' , compact('paymentSetting'));
    }

    /**
     * This function is helpful for update payment's setting data
     * *
     */
    public function update(Request $request, $id)
    {
        // Validations while updating user's form
        $this->validate($request, [
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
            'webhook_url' => 'required'
        ]);

        $input = $request->all();

        $paymentSetting = PaymentSetting::find($id);

        // Update Payment's data data
        $paymentSetting->update($input);

        // Redirect to route
        return redirect()->route('payment-setting.index')->with('success','Your Stripe Data Updated Succesfully');
    }

    public function updatePaymentStatus(Request $request)
    {
        $status = "0";

        // If status is In-active
        if ($request->payment_status == "0") {

            // Update it to Active
            $status="1";
        
        } else{
            // If status is active
            $request->payment_status == "1";

            // Update it to In-active
            $status = "0";   
        }
        $form_data = [
            'status'  =>  $status
        ];

        try {
            // Find id whose status need to update
            $paymentUpdateStatus = PaymentSetting::find($request->id);
            
            // Update status
            $paymentUpdateStatus->status = $status;
            
            // Save value in database
            $paymentUpdateStatus->save();
        
            // Return response
            return response()->json(['id' => $request->id , 'form_data' => $form_data]);
        } catch (\Throwable $th) {
        }    
    }
}
