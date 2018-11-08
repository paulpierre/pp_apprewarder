<?php
/**
 * Analytics API
 */


class Analytics
{
    //class properties
    public $startDate;
    public $endDate;
    public $country;
    public $version;
    public $platform;
    public $apiKey;
    public $groupBy;

    //when this class is instatiated, setup the default class properties
    public function __construct() {
        $this->apiKey = FLURRY_API_KEY_IOS;
        $this->platform = PLATFORM_IOS;
        $this->startDate = date('Y-m-d',time() - 86400 * 7); //last 24 hrs
        $this->endDate = date('Y-m-d',time());
        $this->country = 'ALL';
        $this->groupBy = 'DAYS';
        $this->version = '';
    }

    public function aarkiStats()
    {
        $url = 'https://portal.aarki.com/api/publisher_account_summary.json?token=' . AARKI_API_TOKEN. '&start_date=' . $this->startDate. '&end_date=' . $this->endDate;
        $result = json_decode(file_get_contents($url),true);
        return $result['report']['data'];
    }



    public function adscendStats()
    {
            global $RESOURCE_PATH;
            $cookieFile = $RESOURCE_PATH['VIEW_PATH'] . 'adscend.txt';

            $url = 'https://adscendmedia.com/campstats.php?start_m=5&start_d=7&start_y=2014&end_m=7&end_d=7&end_y=2014&country=&camps%5B%5D='; //https://adscendmedia.com/subidstats.php?start_m=6&start_d=7&start_y=2014&end_m=7&end_d=7&end_y=2014&group_by=subid2
            $hash = md5($url);
            if(isset($_SESSION['adscend'][$hash])) return $_SESSION['adscend'][$hash];
            $fields_string = '';
            $fields = array(
                'submitted' => 1,
                'remember' => 1,
                'email'=>urlencode('##########'),
                'password'=>urlencode('##########'),
                'next'=>urlencode('main.php'),
                'submit'=>'submit'
            );
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();
            $userAgent  = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/11.0';

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookieFile);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_USERAGENT,  $userAgent); // empty user agents probably not accepted
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER,    1);

            //execute post
            $result = curl_exec($ch);
            $doc = phpQuery::newDocument($result);
            $conversions = $doc['tfoot tr td:nth-child(3) b']->text();
            $revenue = $doc['tfoot tr td:nth-child(7) b']->text();
            curl_close($ch);
            $data = array('revenue'=>ltrim($revenue,'$'),'conversions'=>$conversions);
            $_SESSION['adscend'][$hash] = $data;
            return $data;
    }

    public function hasOffersStats($network_id,$network_api_key)
    {
        $params = array(
            'Format' => 'json'
            ,'Target' => 'Report'
            ,'Method' => 'getStats'
            ,'Service' => 'HasOffers'
            ,'Version' => 3
            ,'NetworkId' => $network_id
            ,'api_key' => $network_api_key
            ,'fields' => array(
                'Stat.impressions'
                ,'Stat.clicks'
                ,'Stat.ctr'
                ,'Stat.conversions'
                ,'Stat.payout'
                ,'Stat.sale_amount'

                )

            ,'filters' => array(
                    'Stat.date' => array (
                        'conditional' => 'BETWEEN'
                    ,'values' => array(
                            $this->startDate
                        ,$this->endDate
                        )
                    )
                )
        );
        $url = 'http://api.hasoffers.com/v3/Affiliate_Report.json?' . http_build_query( $params );
        $result = json_decode(file_get_contents( $url ),true);
        return $result['response']['data']['data'][0]['Stat'];
        //print '<pre>' . print_r($result,true) .'</pre>';
        //http://api.hasoffers.com/v3/Affiliate_Report.json //?Method=getStats&api_key=88acedafcf72fbef86f1e852e8818e85803b6c3d0a3764f876922c74f2a9c22b&NetworkId=adactioninteractive


    }


    //daily active users
    public function DAU()
    {
        //lets define the metric we want for DAU and pass to "query" method
        $metric = 'ActiveUsers';
        //lets get the results and send it back to the instance
        $ios_data = $this->DAUiOS();
        $android_data = $this->DAUAndroid();
        unset($d);

        foreach($ios_data as $key=>$item)
        {
          $previous_item = (isset($d[$key]))?intval($d[$key]):0;
          $d[$key] = $previous_item + intval($item);
        }

        foreach($android_data as $key=>$item)
        {
            $previous_item = (isset($d[$key]))?intval($d[$key]):0;
            $d[$key] = $previous_item + intval($item);
        }
        ksort($d);

        return $d;
    }


    public function DAUAndroid()
    {
        $this->platform = PLATFORM_ANDROID;
        $metric = 'ActiveUsers';
        $r = $this->flurry_aggregate_countries($this->query($metric));
        ksort($r);
        return $r;
    }

    public function DAUiOS()
    {
        $this->platform = PLATFORM_IOS;
        $metric = 'ActiveUsers';
        $r = $this->flurry_aggregate_countries($this->query($metric));
        ksort($r);
        return $r;

    }

    //monthly active users
    public function MAU()
    {
        $metric = 'ActiveUsersByMonth';
        return $this->query($metric);
    }

    public function WAU()
    {
        $metric = 'ActiveUsersByWeek';
        return $this->query($metric);
    }

    public function newUsers()
    {

        $ios_data = $this->newUsersiOS();
        $android_data = $this->newUsersAndroid();
        unset($d);

        foreach($ios_data as $key=>$item)
        {
            $previous_item = (isset($d[$key]))?intval($d[$key]):0;
            $d[$key] = $previous_item + intval($item);
        }

        foreach($android_data as $key=>$item)
        {
            $previous_item = (isset($d[$key]))?intval($d[$key]):0;
            $d[$key] = $previous_item + intval($item);
        }

        ksort($d);
        return $d;
    }

    public function newUsersiOS()
    {
        $this->platform = PLATFORM_IOS;
        $metric = 'NewUsers';
        $r=$this->flurry_aggregate_countries($this->query($metric));
        ksort($r);
        return $r;
    }

    public function newUsersAndroid()
    {
        $this->platform = PLATFORM_ANDROID;
        $metric = 'NewUsers';
        $r=$this->flurry_aggregate_countries($this->query($metric));
        ksort($r);
        return $r;    }

    public function userPageViews()
    {
        $metric = 'PageViews';
        return $this->query($metric);
    }

    public function userSessions()
    {
        $metric = 'Sessions';
        return $this->query($metric);
    }

    public function userRetention()
    {
        $metric = 'RetainedUsers';
        return $this->query($metric);
    }

    function flurry_aggregate_countries($d)
    {

        foreach($d['country'] as $country)
        {
            //print '<pre>country:' . $country['@country'] . ' count: ' . count($country['day']) .'</pre>';
            if(isset($country['day']['@date'])){
                $date = $country['day']['@date'];
                $value = intval($country['day']['value']);
                $previous_value = isset($r[$date])?intval($r[$date]):0;
                $r[$date] = $previous_value + $value;
                //print '<pre>date: ' .$date .' val: ' . $value .' previous_val: ' . $previous_value .'</pre>' . PHP_EOL;


            }  else {
                foreach($country['day'] as $item)
                {
                    $date = $item['@date'];
                    $value = intval($item['@value']);
                    $previous_value = isset($r[$date])?intval($r[$date]):0;
                    $r[$date] = $previous_value + $value;
                    //print '<pre>date: ' .$date .' val: ' . $value .' previous_val: ' . $previous_value .'</pre>' . PHP_EOL;

                }
            }

        }
        return $r;

        //exit() '<pre>DATA SET:' .print_r($d,true).' RESULT: ' .print_r($r,true).'</pre>';


    }

    //metrics get passed to query, query handles network call to API and caching based on QUERY_TTL in config.php
    private function query($metric)
    {
        //make sure parameters are set properly

        $apiKey = ($this->platform == PLATFORM_IOS)?FLURRY_API_KEY_IOS:FLURRY_API_KEY_ANDROID;

        //create a unique hash for this particular call, just aggregate the parameters for this particular
        //call to create an "ID" for this report
        $hash = md5($this->platform .$this->startDate . $this->endDate . $this->version . $this->country . $this->groupBy);

        //if the cache did not expire and we have data stored, just return it without
        //having to make a network call to the API
        if(
            time() < $_SESSION['query'][$metric][$hash]['TTL'] &&
            isset($_SESSION['query'][$metric][$hash]['data']))
            return $_SESSION['query'][$metric][$hash]['data'];

        //if the cache expired and we have data, lets remove it so we can
        //make a fresh call and repopulate with fresh data
        if(isset($_SESSION['query'][$metric][$hash])) unset($_SESSION['query'][$metric][$hash]);

        //lets setup the parameters for the query
        $query_params = array(
            'METRIC_NAME'=>$metric,
            'APIACCESSCODE'=>FLURRY_ACCESS_CODE,
            'APIKEY'=>$apiKey,
            'STARTDATE'=>$this->startDate,
            'ENDDATE'=>$this->endDate,
            'COUNTRY'=>$this->country,
            'VERSIONNAME'=>$this->version,
            'GROUPBY'=>$this->groupBy
        );

        //FLURRY_API_URL contains the URL call with dummy place holders for params
        $query = FLURRY_API_URL;

        //lets iterate through the dummy parameters and replace them with our
        //parameters above
        foreach($query_params as $param=> $key)
        {
            $query = str_replace($param,$key,$query);
        }

        //initialize a new curl instance and make the call
        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$query);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //lets decode the JSON and turn it into an associative array
        $result = json_decode(curl_exec($ch),true);

        //lets store our results into the session cache
        $_SESSION['query'][$metric][$hash]['data'] = $result;

        //lets create a new expiry for this result so we can cache it until then
        $_SESSION['query'][$metric][$hash]['TTL'] = time() + QUERY_TTL;

        //return the results back to the requesting method of this class
        return $result;
    }


}

?>