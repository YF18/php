<?php
$url = "https://apis.map.qq.com/ws/geocoder/v1/?location=39.984154,116.307490&key=KUYBZ-CRRCR-CT4WN-WCRS6-HPBJ7-HSBQD";
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$contents = curl_exec($ch);
curl_close($ch);


var_dump($contents);