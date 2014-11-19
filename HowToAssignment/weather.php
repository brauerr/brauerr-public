<!DOCTYPE html>
<html>
  <head>

  </head>
  <body>
  
<?php
$curl = curl_init();
curl_setopt ($curl, CURLOPT_URL, "http://www.ncdc.noaa.gov/cdo-web/api/v2/locations/FIPS:37");
curl_setopt ($curl, CURLOPT_HTMLHEADER, "token:SVyCZAPbcpzDMPOohQqfgtnYoffvejDQ");
$result = json_decode(curl_exec($curl));
curl_close($curl);
echo $result;
?>

  </body>
</html>