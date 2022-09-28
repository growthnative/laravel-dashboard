<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model 
{
    use HasFactory;

    public $table = "payment_details";

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */

    protected $fillable = [
        'transaction_id',
        'card_name',
        'card_number',
        'user_id',
        'amount',
        'currency',
        'exp_month',
        'exp_year',
        'api_data',
        'payment_method',
        'status',
        'payment_setting_id'
    ];

}
