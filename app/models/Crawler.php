<?php
namespace App\Models\Crawler;

use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\CssSelector\CssSelector;

class Crawler
{
    protected $_url;
    protected $_depth;
    protected $_seen = array();
    protected $_host;
    protected $_sitemap;
    protected $_curl;
    protected $_limitUrls;
    protected $_pool;

    public function __construct($url, $depth = 3, $limitUrls = 59)
    {
        $this->_url = $url;
        $this->_depth = $depth;
        $parsed = parse_url($url);
        $this->_host = $parsed['host'];
        $this->_curl = \EpiCurl::getInstance();
        $this->_limitUrls = $limitUrls;
        $this->_pool = new \Pool(10);
    }//__construct()

    public function run()
    {
        // first check for sitemap, if there's no one crawl the site
        if($this->hasSitemap()) {
            $this->getContentFromSitemap();
        } else {
            $this->crawlPage($this->_url, $this->_depth);
        }

        return array_keys($this->_seen);
    }//run()

    public function crawlPage($url, $depth)
    {
        // limit the URL's to 100
        if(count($this->_seen) >= $this->_limitUrls)
            return;

        if(!$this->isValid($url,$depth)) {
           return;
        }

        // add to the seen URL
        $this->_seen[$url] = true;

        // get content an return code
        $req = $this->executeCurl($url);

        if($req->code == 200) {
            $links = $this->processLinks($req->data,$url,$depth);

            foreach($links as $link) {
                if($this->isValid($link,$depth-1)) {
                    $task = new AsyncWebRequest($link);
                    $this->_pool->submit($task);
                }
            }

            $this->newLinks = array();

            // shutdown will wait for current queue to be completed
            $this->_pool->shutdown();
            // garbage collection check / read results
            $this->_pool->collect(function($checkingTask){
                // limit the URL's to 100
                if(count($this->_seen) >= $this->_limitUrls)
                    return $checkingTask->isGarbage();

                $this->_seen[$checkingTask->url] = true;
                // avoid to add empty results
                $searchables = $this->processLinks($checkingTask->response,$checkingTask->url);
                if(is_array($searchables))
                    array_merge($this->newLinks,$searchables);

                return $checkingTask->isGarbage();
            });

            // the third search
            foreach($this->newLinks as $link) {
                if($this->isValid($link,$depth-2)) {
                    $task = new AsyncWebRequest($link);
                    $this->_pool->submit($task);
                }
            }

            // shutdown will wait for current queue to be completed
            $this->_pool->shutdown();
            // garbage collection check / read results
            $this->_pool->collect(function($checkingTask){
                // limit the URL's to 100
                if(count($this->_seen) >= $this->_limitUrls)
                    return $checkingTask->isGarbage();

                $this->_seen[$checkingTask->url] = true;
                return $checkingTask->isGarbage();
            });
        }
    }//crawlPage()

    protected function checkHeaders($url)
    {
        $furl = false;

        // check the response headers
        $headers = get_headers($url);

        // test for 301 or 302
        if(preg_match('/^HTTP\/\d.\d\s+(301|302|304)/',$headers[0])) {
            foreach($headers as $header) {
                if(substr(strtolower($header), 0, 9) == 'location:') {
                    $furl = trim(substr($header, 9, strlen($header)));
                }
            }
        }
        return ($furl) ? $furl : $url;
    }//checkHeaders()

    protected function executeCurl($url)
    {
        $newUrl = $this->checkHeaders($url);

        return $this->_curl->addURL($newUrl);
    }//executeCurl()

    /**
     * Checks a URL and check if is valid for the crawl
     * @param  string   $url    URL to validate
     * @param  integer  $depth  depth of the search
     * @return boolean        true if is valid, false instead
     */
    protected function isValid($url, $depth)
    {
        // if the URL doesn't belongs to the domain of the original URL given,
        // if the depth is 0 and if the URL was already crawled, return false
        $parts = parse_url($url);
        if($parts['host'] !== $this->_host ||
            $depth === 0 ||
            isset($this->_seen[$url])) 
            {
            return false;
        }

        return true;
    }//isValid()

    /**
     * Checks for the existence of a sitemap into a given site
     * @return boolean 
     */
    protected function hasSitemap() {
        // check the robot
        $robot = $this->executeCurl($this->_url . '/robots.txt');

        if($robot->code === 200) {
            // search for the sitemap.xml
            $pattern = '/(http|https):.*/i';
            preg_match($pattern,(string)$robot->data,$matches);

            if(!$matches)
                return false;

            // retrieve the sitemap
            $mapResponse = $this->executeCurl($matches[0]);

            if(!$mapResponse->data)
                return false;

            // check for errors
            libxml_use_internal_errors(true);
            $doc = simplexml_load_string((string)$mapResponse->data);

            if(!$doc) 
                return false;
            else{
                $this->_sitemap = (string)$mapResponse->data;
                return true;
            }
        }
        
        return false;
    }//hasSitemap()

    /**
     * get the contents of the obtained sitemap of a site
     * and save them into $this->_seen
     */
    protected function getContentFromSitemap()
    {
        $crawler = new \DOMDocument();
        $crawler->loadXml($this->_sitemap);
        // select all tags 'loc' that holds the URL
        $locs = $crawler->getElementsByTagName('loc');
        $first = $locs->item(0);

        // if it is a sitemap of sitemaps, we better crawl the site
        if(preg_match('/.xml$/',$first->nodeValue))
            $this->crawlPage($this->_url, $this->_depth);

        foreach($locs as $element) {
            $this->_seen[$element->nodeValue] = true;

            // if the count of URL's get the maximun, it returns
            if(count($this->_seen) >= $this->_limitUrls)
                return;
        }
    }//getConentFromSitemap()

    protected function _printResult($url, $depth, $httpcode)
    {
        ob_end_flush();
        $currentDepth = $this->_depth - $depth;
        $count = count($this->_seen);
        echo "N::$count,CODE::$httpcode,DEPTH::$currentDepth URL::$url <br>";
        ob_start();
        flush();
    }

    /**
     * processLinks process the content of a page search for all the "a" tags
     * and clean the link inside. Calls ::crawlPage for each link found
     * @param  string   $content  the content of a page
     * @param  string   $url      the URL who belongs the content
     * @param  integer  $depth    the depth of levels to search links
     */
    protected function processLinks($content, $url)
    {
        if(!$content || !$url)
            return;

        $crawler = new DomCrawler($content);
        $anchors = $crawler->filter('a');
        $links = [];

        foreach($anchors as $element) {
            $href = $element->getAttribute('href');

            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }
            $links[] = $href;
            // $this->crawlPage($href,$depth - 1);
        }
        return $links;
    }//processLinks()
    
}//Crawler