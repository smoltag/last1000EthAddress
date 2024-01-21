<?php
$api_key = 'your-api-key';
$apiUrl = "https://api.etherscan.io/api";
$params = array(
    'module' => 'proxy',
    'action' => 'eth_blockNumber',
    'apikey' => $api_key
);
$url = $apiUrl . '?' . http_build_query($params);
$response = file_get_contents($url);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['result'])) 
    {
        $end_block = hexdec($data['result']);
    } 
} 
//----------
$start_block = $end_block-1000;
echo $start_block." ".$end_block."<hr>";
$page = '1';
$offset = '1000';
$api_url = "https://api.etherscan.io/api?module=account&action=txlistinternal&startblock={$start_block}&endblock={$end_block}&page={$page}&offset={$offset}&sort=asc&apikey={$api_key}";

$response = file_get_contents($api_url);

if ($response === false) {
    die('Erreur lors de la récupération des données depuis l\'API.');
}

$data = json_decode($response, true);

if ($data['status'] == '1') {
    $internal_transactions = $data['result'];
    
$wallets=array();

    foreach ($internal_transactions as $internal_transaction) 
    {
        $from_address = $internal_transaction['from'];
        $to_address = $internal_transaction['to'];
        $timestamp = $internal_transaction['timeStamp'];
        $date = date('Y-m-d H:i:s', $timestamp);
        if($from_address!="") $wallets[] = $from_address;
        if($to_address!="") $wallets[] = $to_address;
    }
} else {
    echo 'Erreur de l\'API: ' . $data['message'];
}

foreach(array_unique($wallets) as $key=>$value)
{
    echo $value."<br>";
}
?>
