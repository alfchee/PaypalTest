<?php namespace App\Models\Paypal;

use PayPal\Api\PaymentExecution;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;

class PaypalPayment 
{
    protected $apiContext;

    public function __construct($apiContext)
    {
        $this->apiContext = $apiContext;
    }//__construct()

    public function makePaymentUsingPayPal($total, $currency, $paymentDesc, $returnUrl)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // specify the payment ammount
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);

        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl . '&success=true');
        $redirectUrls->setCancelUrl($returnUrl . '&success=false');

        $payment = new Payment();
        $payment->setRedirectUrls($redirectUrls);
        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        try{
            $payment->create($this->apiContext);
        } catch(Exception $e) {
            throw new Exception($e);
        }

        return $payment;
    }//makePaymentUsingPayPal()

    /**
     * Retrieves the payment information based on PaymentID from Paypal APIs
     *
     * @param $paymentId
     *
     * @return Payment
     */
    public function getPaymentDetails($paymentId)
    {
        $payment = Payment::get($paymentId, $this->apiContext);
        return $payment;
    }//getpaymentDetails()

    /**
     * Completes the payment once buyer approval has been
     * obtained. Used only when the payment method is 'paypal'
     * 
     * */
    public function executePayment($paymentId, $payerId)
    {
        $payment = $this->getPaymentDetails($paymentId);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        $payment = $payment->execute($paymentExecution, $this->apiContext);

        return $payment;
    }//executePayment()
}