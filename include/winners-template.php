<?php
/*
template name: winners
*/
get_header();

?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
//  API url
$url = 'https://www.anzrottaps.com/api/Games/Get_Winners';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json'
]);

$response = curl_exec($curl);
curl_close($curl);
echo "<pre>";
echo json_encode($response). PHP_EOL;
echo "</pre>";

get_footer();
