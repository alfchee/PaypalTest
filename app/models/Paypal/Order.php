<?php namespace App\Models\Paypal;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{
	protected $table = 'orders';

	public $timestamps = true;

	protected $fillable = ['user_name', 'payment_id', 'state','amount','description'];
}