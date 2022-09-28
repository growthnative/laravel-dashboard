<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe;
use Session;
use Auth;
use App\Models\PaymentDetails;
use App\Models\PaymentRefund;
use Config;
use App\Models\PaymentSetting;

/*
    |--------------------------------------------------------------------------
    | Stripe Payment Controller
    |--------------------------------------------------------------------------
    |
    | This Controller is helpful for handling payment detials (through stripe)
*/
class StripePaymentController extends Controller
{
    public function __construct()
    { 
        $this->middleware('permission:payment-read|paymemt-write|paymemt-edit|paymemt-delete', ['only' => ['stripeDetails','paymentstripe' , 'makePayment']]);
    }

    /**
     * This function is helpful for displaying payment details as per the user's
     * *
    */
    public function stripeDetails(Request $request)
    {    
        //Pagination count from config file
        $items_per_page = Config::get('global.pagination.items_per_page');

        // Get payment details
        $paymentDetails = PaymentDetails::where('user_id' , Auth::user()->id)->orderBy('created_at' , 'DESC');

        //Get Params of search
        $card_number = trim($request->query('card_number'));

        $paymentDate = trim($request->query('created_at'));

        // Serach by card number
        if(!empty($card_number))
        {
            $paymentDetails = $paymentDetails->where('card_number','LIKE','%'.$card_number.'%');
        }

        // Serach by payment date
        if(!empty($paymentDate))
        {
            $paymentDetails = $paymentDetails->where('created_at','LIKE','%'.$paymentDate.'%');
        }

        // Get payment details
        $paymentDetails = $paymentDetails->paginate($items_per_page);

        // Return to view
        return view('admin.stripe.details' , compact('paymentDetails'));
    }

    /**
     * This function is helpful for creating payment form
     * *
    */
    public function paymentstripe()
    {
        // Get Payment Setting data(only those whose status is 'Active')
        $stripeSettingData = PaymentSetting::where('status' , '1')->get();
       
        return view('admin.stripe.create' , compact('stripeSettingData'));
    }

    /**
     * This function is helpful for creating payment
     * *
    */
    public function makePayment(Request $request)
    {
        // Get logged-in user data
        $userData = Auth::user();

        // Get requested amount 
        $stripeAmount = $request->amount * 100;

        // Validations while saving payment details on databse
        $this->validate($request, [
            'expire_month'   => 'required',
            'expire_year'   => 'required',
            'card_name' => 'required',
        ]);

        // Get stripe secret key(from stripe token)
        $stripeSecretKey = PaymentSetting::where('stripe_key' , $request->custom_secret)->get(['id' , 'stripe_secret'])->toArray();
       
       
        if(isset($stripeSecretKey) && (!empty($stripeSecretKey)))
        {
            // Get stripe secret 
            $stripeSecret = $stripeSecretKey[0]['stripe_secret'];
        } else{
            // Get Stripe secret key from .env
            $stripeSecret = env('STRIPE_SECRET');
        }
       
        // Stripe key
        \Stripe\Stripe::setApiKey($stripeSecret);
       
        // Create payment
        $charge = \Stripe\Charge::create([
            'source' => $request->stripeToken,
            'description' => "test data",
            'amount' => $stripeAmount,
            'currency' => 'usd',
            "metadata" => array("email" => $userData->email, "name" => $userData->first_name)
        ]);
        
        // Transform success code
        if($charge->status == 'succeeded'){
            $status = 2; 
        } elseif($charge->status == 'canceled'){
            $Status = 3;
        } else{
            $Status = 1;
        }

        // Save payment details in database
        $payment = PaymentDetails::create([
            'card_name' => $request->card_name,
            'card_number' => $charge->payment_method_details->card->last4,
            'transaction_id' => $charge->id,
            'exp_month' => $charge->payment_method_details->card->exp_month,
            'exp_year' => $charge->payment_method_details->card->exp_year,
            'currency' => $charge->currency,
            'status' => $status,
            'payment_method' =>'stripe',
            'user_id' => $userData->id,
            'api_data' => json_encode($charge),
            'amount' => $request->amount,
            'payment_setting_id' => $stripeSecretKey[0]['id'] ?? NULL,
        ]);
       
        // Return back with error
        return redirect()->route('stripeDetails')->with('success','YOUR PAYMENT HAS BEEN PROCCESSED.');
    }

    /**
     * This function is helpful for refund payment(it may be partial or fully)
     * *
    */
    public function returnPayment(Request $request)
    {
        // Get requested refunded amount 
        $stripeAmount = $request->amount * 100;
    
        // Get details of payment which need to be refunded
        $paymentDetails = PaymentDetails::where('id' , $request->payment_id)->first();

        $paymentSettingData = PaymentSetting::where('id', $paymentDetails->payment_setting_id)->pluck('stripe_secret')->toArray();
    
        // Validations while saving payment details on databse
        $this->validate($request, [
            'amount'   => 'required',
        ]);
    
        // Check that requested amount should not be less than 0 and not more than filled amount
        if($request->amount > 0 && $request->amount > $request->payment_amount)
        {
            // Return back with error
            return back()->withInput()->with('error',"Refund amount must not be more than $$request->payment_amount");
        }

        // Get stripe secret key from .env
        if(isset($paymentSettingData))
        {
            // Get stripe secret 
            $stripeSecret = $paymentSettingData[0];
        } else{
            // Get Stripe secret key from .env
            $stripeSecret = env('STRIPE_SECRET');
        }

        // Stripe key
        \Stripe\Stripe::setApiKey($stripeSecret);
    
        $refund = \Stripe\Refund::create([
            'charge' => $paymentDetails->transaction_id,
            'amount' => $stripeAmount,
            // 'reason' => 'refund data'
        ]);

        // Update data in payment refund table
        $updatePayment= PaymentRefund::updateOrCreate(['id'=>$request->payment_id],[
            'payment_id' => $request->payment_id,
            'user_id' => Auth::user()->id,
            'refund_amount' => $request->amount,
            'transaction_refund_id' => $refund->id
        ]);

        // Redirect to view
        return redirect()->route('stripeDetails')->with('success','Your refunded payment initiated successfully, It may take a few days for the money to reach the customers bank account.');
    }

    /**
     * This function is helpful for show paymenet data
     * *
     */
    public function showPayment($id)
    {
        // Get payment's details as per the specific id
        $paymentDetails = PaymentDetails::find($id);

        return view('admin.stripe.show', compact('paymentDetails'));
    }

    /**
     * This function is helpful for manual sync stripe data with our database
     * *
     */
    public function reterivePayment()
    {
        // Get stripe secret key from .env
        // $stripeSecret = env('STRIPE_SECRET');
        // \Stripe\Stripe::setApiKey($stripeSecret);

        $stripe = new \Stripe\StripeClient(
            'sk_test_51LfMGrFojUm89nkM3d9JrAVl4kYzGxwkiD1HPjDya3jZ2AUDcaPJqK4hSlGTy37WgIYKFzOVx2gKz0ofiNfn2Nlt00vP2HO27n'
        );


        // Get data from table 'Payment-Details'
        $paymentDetails = PaymentDetails::all();
        
        foreach($paymentDetails as $paymentData)
        {
            // find transaction_id fro database
            $tranactionid = $paymentData->transaction_id;

            // Here strope method is used to reterive data
            $stripeDetails = $stripe->charges->retrieve(
                $tranactionid
            );
            
            // Get refund data from api response
            $refundData = $stripeDetails->refunds->data;
            
            // If $refundData is not equal to 0
            if(count($refundData) > 0)
            {
                foreach($refundData as $details)
                {
                    // Find refund id from api response
                    $refundId = $details->id;

                    // Get refund-id which is alredy stored in databse
                    $refundTranactionData = PaymentRefund::where('transaction_refund_id' , $refundId)->count();
                    
                    // save only those refund data whose data is not saved in database
                    if($refundTranactionData == 0)
                    {
                        // Get amount from api and divide by 100 
                        $amountData = $details->amount / 100;
                        
                        // Save refund data (which is done from stripe dashboard) on our dashboard
                        $payment = PaymentRefund::create([
                            'transaction_refund_id' => $refundId,
                            'payment_id' => $paymentData->id,
                            'user_id' => 1,
                            'refund_amount'=> $amountData
                        ]);  
                    }
                }
            } 
        }
        echo 'Data Updated succesfully';
        die;
    }

    // public function syncRefundPayment()
    // {
    //     $payload = @file_get_contents('php://input');
    //     $event = null;
        
    //     try {
    //         $event = \Stripe\Event::constructFrom(
    //             json_decode($payload, true)
    //         );
    //     } catch(\UnexpectedValueException $e) {
    //         // Invalid payload
    //         http_response_code(400);
    //         exit();
    //     }
    //     // Getting response
    //     $paymentIntent = $event->data->object;

    //     // Get payment data whose tranaction id is getting from response
    //     $paymentData = PaymentDetails::where('transaction_id' , $paymentIntent->id)->first();

    //     // Get payment id
    //     $paymentId = $paymentData->id;

    //     if(count((array)$paymentId) > 0)
    //     {
    //         // Get refund data from api response
    //         $refundData = $paymentIntent->refunds->data;
    //         if(count($refundData) > 0)
    //         {
    //             foreach($refundData as $details)
    //             {
    //                 // Find refund id from api response
    //                 $refundId = $details->id;

    //                 // Get refund-id which is alredy stored in databse
    //                 $refundTranactionData = PaymentRefund::where('transaction_refund_id' , $refundId)->count();

    //                 // save only those refund data whose data is not saved in database
    //                 if($refundTranactionData == 0)
    //                 {
    //                     // Get amount from api and divide by 100 
    //                     $amountData = $details->amount / 100;
                        
    //                     // Save refund data (which is done from stripe dashboard) on our dashboard
    //                     $payment = PaymentRefund::create([
    //                         'transaction_refund_id' => $refundId,
    //                         'payment_id' => $paymentId,
    //                         'user_id' => 1,
    //                         'refund_amount'=> $amountData
    //                     ]);  
    //                 }
    //             }
    //         }
    //     }
    // }

    public function syncRefundPayment()
    {
        $payload = @file_get_contents('php://input');
        $event = null;
        
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }
        // Getting response
        $paymentIntent = $event->data->object;

        // Get payment data whose tranaction id is getting from response
        $paymentData = PaymentDetails::where('transaction_id' , $paymentIntent->id)->first();

        // Get payment id
        $paymentId = $paymentData->id;

        if(count((array)$paymentId) > 0)
        {
            // Get refund data from api response
            $refundData = $paymentIntent->refunds->data;
            if(count($refundData) > 0)
            {
                foreach($refundData as $details)
                {
                    // Find refund id from api response
                    $refundId = $details->id;

                    // Get refund-id which is alredy stored in databse
                    $refundTranactionData = PaymentRefund::where('transaction_refund_id' , $refundId)->count();

                    // save only those refund data whose data is not saved in database
                    if($refundTranactionData == 0)
                    {
                        // Get amount from api and divide by 100 
                        $amountData = $details->amount / 100;
                        
                        // Save refund data (which is done from stripe dashboard) on our dashboard
                        $payment = PaymentRefund::create([
                            'transaction_refund_id' => $refundId,
                            'payment_id' => $paymentId,
                            'user_id' => 1,
                            'refund_amount'=> $amountData
                        ]);  
                    }
                }
            }
        }
    }

}
