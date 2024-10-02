<?php

include '../vendor/autoload.php';

use Patrickmaken\Web2Sms\Client as W2SClient;

W2SClient::setConfig([
    'api_user_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
    'api_key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
]);

$telephone = '+237699887766';
$text = 'Ceci est un message de test.';
$senderID = 'MyApp';

$response = W2SClient::sendSMS($telephone, $text, $senderID);
var_dump($response);