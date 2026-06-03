<?php
require __DIR__ . '/vendor/autoload.php';
$apiKey = 'AIzaSyAwG5CBYYfBNfGb48O3fMaIs8u7LA3NDj8';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";
$response = file_get_contents($url);
echo $response;
