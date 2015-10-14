<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Paypal\PaypalBootstrap;
use App\Models\Paypal\PaypalPayment;
use App\Models\Paypal\Order;

class PaypalController extends BaseController
{
    protected $apiContext;

    public function __construct()
    {
        $this->apiContext = PaypalBootstrap::getApiContext();
    }//__construct()

    public function checkout(Request $request) 
    {
        $points = $request->input('points');

        return view('payments.checkout',['points' => $points]);
    }//checkout()

    public function placeOrder(Request $request)
    {
        $input = $request->all();
        $ppp = new PaypalPayment($this->apiContext);
        $returnUrl = route('payments.orderconfirmation');
        $order = new Order(array('user_name' => $_ENV['APP_USERNAME'], 'amount' => $input['total'], 'description' => $input['description']));
        $order->save();
        $returnUrl .= '?orderId='.$order->id;

        $payment = $ppp->makePaymentUsingPayPal($input['total'],$input['currency'],$input['description'],$returnUrl);

        return redirect($payment->getApprovalLink());
    }//placeOrder()

    public function orderConfirmation(Request $request)
    {
        $inputs = $request->all();

        if($inputs['success'] == 'true' && isset($inputs['PayerID']) && isset($inputs['orderId'])) {
            $order = Order::findOrFail($inputs['orderId']);
            $ppp = new PaypalPayment($this->apiContext);
            $payment = $ppp->executePayment($inputs['paymentId'], $inputs['PayerID']);
            $order->state = $payment->getState();
            $order->payment_id = $inputs['paymentId'];
            $order->save();

            return view('payments.orderConfirmation',
                ['message' => 'Your payment was successful. Your order ID is '.$order->payment_id,
                    'type' => 'success'] );
        } else {
            return view('payments.orderConfirmation',
                ['message' => 'Your payment was canceled',
                    'type' => 'error']);
        }
    }//orderConfirmation()
}