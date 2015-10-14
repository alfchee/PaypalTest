<?php

namespace App\Models\Crawler;

class AsyncWebRequest extends \Collectable 
{
    public $response = null;
    public $url = null;

    public function __construct($url) 
    {
        $this->url = $url;
    }//__construct()

    public function run()
    {
        // $curl = EpiCurl::getInstance();      
        //$newUrl = $this->checkHeaders($this->url);
        try{
            $this->response =  file_get_contents($this->url); //$curl->addURL($url);    
        } catch(Exception $e) {
            throw new Exception($e);
        }
    }//run()
}//WorkerThreads