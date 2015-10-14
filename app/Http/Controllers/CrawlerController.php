<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Crawler\Crawler;
use App\Models\Crawler\CKMoz;

class CrawlerController extends BaseController
{
    public function crawlSite(Request $request)
    {
        $startURL = $request->input('url');
        $urls = array();

        $crawler = new Crawler($startURL,3);
        $urls = $crawler->run();

        if(!is_array($urls))
            throw new Exception("There's no URL's to make the consult.");

        $groups = array_chunk($urls, 10);
        $metricQ = new CKMoz($_ENV['ACCESS_ID'], $_ENV['SECRET_KEY']);
        $cols = array('title','canonURL','ExEquityLinks','links','mozRankURL','mozRankSubDomain','httpCode','pageAuth','domainAuth');
        $result = array();

        foreach($groups as $group) {
            $rs = $metricQ->batchedQuery($group,$cols);

            // if result is an error of authentication
            if($rs->code == 401)
                return response($rs->data)->header('Content-Type','application/json');

            $datas = json_decode($rs->data,true);
            foreach($datas as $key => $data) {
                $result[] = array('url' => $group[$key], 'data' => $data);
            }
        }

        return response()->json($result);
    }//crawlSite()

    public function siteManager(Request $request)
    {
        $urls = $request->input('urls');

        return response()->json($urls);
    }//siteManager()
}
