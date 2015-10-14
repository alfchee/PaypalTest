<?php namespace App\Models\Paypal;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalBootstrap 
{

    public static function getApiContext()
    {
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com
        $apiContext = new ApiContext(
                new OAuthTokenCredential($_ENV['PAYPAL_CLIENT_ID'],$_ENV['PAYPAL_SECRET'])
            );

        // setting some configurations
        $apiContext->setConfig(array(
                'mode' => $_ENV['PAYPAL_MODE'],
                'log.LogEnabled' => true,
                'log.FileName' => base_path('storage/logs/paypal.log'),
                'log.LogLevel' => 'DEBUG',
                'cache.enabled' => true
            ));

        return $apiContext;
    }//getApiContext()  

}