<?php
$url = 'https://www.googleapis.com/userinfo/v2/me';

$token = "ya29.ewDZgSEMT_ShME73PsAAPMb-hO47tpqY8mjjQXWkotjMuk7dZqPYvZYr";

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
echo $result;