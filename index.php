<?php
include 'CitiesWeatherDetails.php';


$api = new CitiesWeatherDetails('https://api.musement.com/api/v3/cities', getenv('WEATHER_API_KEY'));

$api->resultsToStdout();

?>
