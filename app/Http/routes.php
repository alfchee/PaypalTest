<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->group(['prefix' => 'crawler'], function($app) {
    $app->get('/',function() use ($app) {
        return view('crawler.crawler');
    });
    $app->post('query',['uses' => 'App\Http\Controllers\CrawlerController@crawlSite', 'as' => 'crawler.query']);
    $app->post('site-manager', ['uses' => 'App\Http\Controllers\CrawlerController@siteManager','as' => 'crawler.siteManager']);
});

$app->group(['prefix' => 'payment'], function($app) {
    $app->get('/',[
                    'uses' => 'App\Http\Controllers\PaypalController@checkout', 
                    'as' => 'payments.checkout'
                ]);
    $app->post('/place-order',[
                                'uses' => 'App\Http\Controllers\PaypalController@placeOrder',
                                'as' => 'payments.placeorder'
                            ]);
    $app->get('/order-confirmation',[
                                        'uses' => 'App\Http\Controllers\PaypalController@orderConfirmation',
                                        'as' => 'payments.orderconfirmation'
                                    ]);
});