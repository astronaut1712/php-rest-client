<?php
require("restclient.php");
$client = new RestClient();
$client->get("http://audio.nxquang.com/api/categories");
var_dump($client->getResBody());

?>
