<?php
namespace App\Models\Crawler;

class CKMoz
{
    private $secretKey;
    private $accessID;

    protected $curl;
    protected $metricsUrl = 'http://lsapi.seomoz.com/linkscape/url-metrics/';
    protected $colValues = array(
            'title' => 1, 'canonURL' => 4, 'subdomain' => 8,
            'rootDomain' => 16, 'ExEquityLinks' => 32, 'EquityLinks' => 256, 'links' => 2048,
            'mozRankURL' => 16384, 'mozRankSubDomain' => 32768, 'httpCode' => 536870912,
            'pageAuth' => 34359738368, 'domainAuth' => 68719476736, 'timeLastCrawl' => 144115188075855872
        );

    public function __construct($access, $secret) 
    {
        $this->accessID = $access;
        $this->secretKey = $secret;
        $this->curl = \EpiCurl::getInstance();
    }//__construct()

    /**
     * Returns an array that contains the calculated expiration time
     * and the signature in the proper cypher
     * 
     * @return array contains the expires and signature
     */ 
    protected function generateSignature()
    {
        // set the expire time to 5 minutes
        $expires = time() + 300;

        // put the parameters in a different line
        $stringToSign = $this->accessID."\n".$expires;

        // get the binary output of the hmac has
        $binarySignature = hash_hmac('sha1', $stringToSign, $this->secretKey, true);

        // Base64-encode and url-encode
        $urlSafeSignature = urlencode(base64_encode($binarySignature));

        return array('expires' => $expires, 'signature' => $urlSafeSignature);
    }//generateSignature()

    protected function getCols($cols)
    {
        $value = 0;

        foreach($cols as $key) {
            $value += $this->colValues[$key];
        }

        return $value;
    }//getCols()

    protected function curlInit($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }//curlInit()

    public function httpRequest($method = null, $url = null, $params = null, $isMultipart = false) {
        if(empty($method) || empty($url))
            return false;

        switch ($method) {
            case 'GET':
                return $this->httpGet($url, $params);
                break;
            
            case 'POST':
                return $this->httpPost($url, $params);
                break;
        }
    }//httpRequest()

    protected function buildHttpQueryRaw($params) 
    {
        $result = '';
        // add the parameters to the URL
        if(count($params['request']) > 0) {
            $result .= '?';

            foreach($params['request'] as $k => $v) {
                $result .= "{$k}={$v}&";
            }
            $result = substr($result,0,-1);
        }
        return $result;
    }//buildHttpQueryRaw()

    protected function httpGet($url, $params)
    {
        $url .= $this->buildHttpQueryRaw($params);

        $ch = $this->curlInit($url);
        $resp = $this->executeCurl($ch);

        return $resp;
    }//httpGet()

    protected function httpPost($url, $params)
    {
        $url .= $this->buildHttpQueryRaw($params);

        $ch = $this->curlInit($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params['urls']));

        $resp = $this->executeCurl($ch);

        return $resp;
    }//httpPost()

    protected function executeCurl($ch)
    {
        return $this->curl->addCurl($ch);
    }//executeCurl()

    protected function getParams($cols)
    {
        $params['request']['Cols'] = $this->getCols($cols);
        $params['request']['AccessID'] = $this->accessID;
        $sig = $this->generateSignature();
        $params['request']['Expires'] = $sig['expires'];
        $params['request']['Signature'] = $sig['signature'];

        return $params;
    }//getParams()

    public function query($url, $cols)
    {
        // prepare the URL with the URL to consult encoded
        $requestUrl = $this->metricsUrl . urlencode($url);
        // get the params for API
        $params = $this->getParams($cols);

        return $this->httpRequest('GET', $requestUrl, $params);
    }//query()

    public function batchedQuery($urls, $cols)
    {
        if(!is_array($urls))
            return; 

        // get the params for API
        $params = $this->getParams($cols);

        // make sure that only will to query 10 URL's
        if(count($urls) > 10) {
            while(count($urls) > 10) {
                array_pop($urls);
            }
        }
        $params['urls'] = $urls;

        return $this->httpRequest('POST', $this->metricsUrl, $params);
    }//batchedQuery()
}