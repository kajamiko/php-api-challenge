<?php


class CitiesWeatherDetails
{

    protected $apiUrl;

    protected $weatherApiKey;

    public function __construct($apiUrl, $weatherApiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->weatherApiKey = $weatherApiKey;
    }

    public function callAPI(){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return json_decode($result, true);
    }

    public function callMulti($urls) {

        $mh = curl_multi_init();
        foreach($urls as $key => $value){
            $ch[$key] = curl_init($value);
            curl_setopt($ch[$key], CURLOPT_HEADER, 0);
            curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh,$ch[$key]);
        }

        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        $forecasts_array = [];
        foreach(array_keys($ch) as $key){

            $result = json_decode(curl_multi_getcontent($ch[$key]), true);
            if(!$result){die("Connection Failure");}
            if(isset($result['error'])){
                $forecasts_array[] = array('current' => "Unavailable", 'tomorrow' => "Unavailable");
            }
            else {
                $forecasts_array[] = array('current' => $result['current']['condition']['text'], 'tomorrow' => $result['forecast']['forecastday'][0]['day']['condition']['text']);

            }

            curl_multi_remove_handle($mh, $ch[$key]);
        }

        curl_multi_close($mh);

        return $forecasts_array;
    }


    public function getUrls() {

        return array_map(function($entry) {
            return "http://api.weatherapi.com/v1/forecast.json?key=" . $this->weatherApiKey .'&q=' . $entry['latitude'] . "," .   $entry['longitude']
                    . '&days=2';
        }, $this->callAPI());

    }

    public function resultsToStdout() {

        $response = $this->callAPI();

        $urls =  $this->getUrls();

        if(!empty($urls)) {
            $forecasts_array = $this->callMulti($urls);
        }

        if(count($response) != count($forecasts_array)){
            die("Application stopped as the results may not be accurate");
        }

        foreach ($response as $key=>$entry) {
            echo "Processed city " . $entry['name'] . " | ". $forecasts_array[$key]['current']
                . ' - ' .
                $forecasts_array[$key]['tomorrow'] ;
            echo "\n";
        }
    }

}