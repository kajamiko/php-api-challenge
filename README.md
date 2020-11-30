# The API weather challenge

## What does it do

The application calls Musement's catalogue for the list of cities and then gets a forecast from api.weatherapi.com
for each of them for today and tomorrow.

## Installation and using

1. Set an environmental variable with the name of WEATHER_API_KEY with the value of a valid weatherapi.com site key,
in order for the application to work.

2. Launch the app

`php index.php > "result.txt"`


# Step 2 of the assessment (API design)


## What it does

This is weather API design. In this project, I designed two endpoints. One accepts PUT method with array of objects - all of them describing weather conditions in the location specified by a path parameter, for at least two days. The second one fetches weather for a city for a day specified by a path parameter.


## Description

I have created this API project using OpenApi 3.0.0 with [Swagger](https://editor.swagger.io/) editor and saved in openapi.yaml and openapi.json files.

