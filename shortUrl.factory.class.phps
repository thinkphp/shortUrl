<?php

    /* Abstract class ShortUrl */
 
    abstract class ShortUrl {

        protected $data;
 
            abstract protected function api();

            abstract protected function execute($url);

            public function getTinyUrl() {

                   return $this->data;
            }

            protected function get($url) {

                 $ch = curl_init();

                 curl_setopt($ch,CURLOPT_URL,$url);     

                 curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

                 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

                 $data = curl_exec($ch);

                 curl_close($ch); 

                 if(empty($data)) {return 'Error retrieving data. Try again.';}

                           else 
                                  {return $data;}
            } 


            final protected function validUrl($longurl){
 
                if(!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $longurl)) {

                     throw new Exception("The url is not valid. Please try again.");
                }
           }
     }

     /* Concret class TinyUrl */
      
     class TinyUrl extends ShortUrl { 

        private $url; 

           public function __construct($longurl) {

                $this->validUrl($longurl);

                $this->url = urlencode($longurl);

                $apiurl = sprintf($this->api(),$this->url);

                $this->execute($apiurl);
           } 

           public function execute($url) {

                $this->data = $this->get($url); 
           }
           

           public function api() {

               return "http://tinyurl.com/api-create.php?url=%s";
           }

     }

     /* Concret class Bitly */

     class Bitly extends ShortUrl { 

        private $longurl; 

           public function __construct($longurl,$user,$api) {

                $this->validUrl($longurl);

                $this->longurl = urlencode($longurl);

                $apiurl = sprintf($this->api(),$this->longurl,$user,$api);

                $this->execute($apiurl);
           } 

           public function execute($url) {

               $content = $this->get($url);

               $xml = simplexml_load_string($content);

               $shortUrl = $xml->results->nodeKeyVal->shortUrl;  
        
               $this->data = $shortUrl;             
           }
           
           public function api() {

               return "http://api.bit.ly/shorten?version=2.0.1&longUrl=%s&login=%s&apiKey=%s&format=xml";
           }

     }

     /* Concret class Trim */

     class Trim extends ShortUrl { 

        private $longurl; 

           public function __construct($longurl) {

                $this->validUrl($longurl);

                $this->longurl = urlencode($longurl);

                $apiurl = sprintf($this->api(),$this->longurl);

                $this->execute($apiurl);
           } 

           public function execute($url) {

               $content = $this->get($url);

               $xml = new simpleXMLElement($content);

               $shortUrl = $xml->url;
               
               $this->data = $shortUrl;  
           }
           
           public function api() {

               return "http://api.tr.im/v1/trim_url.xml?url=%s";;
           }

     }


     /* Concret class Isgd */

     class Isgd extends ShortUrl { 

        private $longurl; 

           public function __construct($longurl) {

                $this->validUrl($longurl);
 
                $this->longurl = urlencode($longurl);

                $apiurl = sprintf($this->api(),$this->longurl);

                $this->execute($apiurl);
           } 

           public function execute($url) {

               $content = $this->get($url);
               
               $this->data = $content;  
           }
           
           public function api() {

               return "http://is.gd/api.php?longurl=%s";
           }

     }


     /* Concret class Unu */

     class Unu extends ShortUrl { 

        private $longurl; 

           public function __construct($longurl) {

                $this->validUrl($longurl);

                $this->longurl = urlencode($longurl);

                $apiurl = sprintf($this->api(),$this->longurl);

                $this->execute($apiurl);
           } 

           public function execute($url) {

               $content = $this->get($url);
               
               $this->data = $content;  
           }
           
           public function api() {

               return "http://u.nu/unu-api-simple?url=%s";
           }

     }




     /* Concret class Digg */

     class Digg extends ShortUrl { 

        private $longurl; 

           public function __construct($longurl,$apikey) {

                $this->validUrl($longurl);

                ini_set('user_agent','Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');

                $this->longurl = urlencode($longurl);

                $apiurl = sprintf($this->api(),$apikey,$this->longurl);

                $this->execute($apiurl);
           } 

           public function execute($url) {

                $xml = file_get_contents($url);
               
                $data = preg_match('/short_url="(.*)"/isU',$xml,$matches);
            
                $this->data = $matches[1];
           }
           
           public function api() {

              return "http://services.digg.com/url/short/create?type=xml&appkey=%s&url=%s";
           }

     }


     /* class Factory using method Factory*/

     class Factory {

         public static function create($name, $url, $user='', $key='') {

                switch(strtolower($name)) {

                       case 'bitly': 

                         $obj = new Bitly($url,$user,$key);

                         break;

                       case 'tinyurl': 

                         $obj = new tinyUrl($url);

                         break;
 
                       case 'trim': 
  
                         $obj = new Trim($url);

                         break;  

                      case 'isgd': 
  
                         $obj = new Isgd($url);

                         break;  

                      case 'unu': 
  
                         $obj = new Unu($url);

                         break;  

                      case 'digg': 
  
                         $obj = new Digg($url,$key);

                         break;  


                }//end switch

              return $obj;
         }

      }

      /* How to Usage */

     /*
           //Holds in variable '$longUrl' the below address post
           $longUrl = 'http://www.wait-till-i.com/2009/11/02/getting-a-list-of-flickr-photos-by-location-andor-search-term-with-a-yql-open-table/';

         //$ob = Factory::create('bitly',$longUrl,"thinkphp","R_0cf8415f0c3f9fcfd867ce7613e43fc7");
         //$ob = Factory::create('tinyurl',$longUrl);
         //$ob = Factory::create('trim',$longUrl);
         //$ob = Factory::create('isgd',$longUrl);
         //$ob = Factory::create('unu',$longUrl);
         //$ob = Factory::create('digg',$longUrl,"thinkphp","http://thinkphp.ro");
 

         try {
             
             $ob = Factory::create('bitly',$longUrl,"thinkphp","R_0cf8415f0c3f9fcfd867ce7613e43fc7");

             echo$ob->getTinyUrl();

         }catch(Exception $e) {echo$e->getMessage();}

         $output = highlight_file($_SERVER['SCRIPT_FILENAME']);

         echo$output;
     */

?>