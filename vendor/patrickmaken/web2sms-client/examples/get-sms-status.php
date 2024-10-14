<?php

include '../vendor/autoload.php';

use Patrickmaken\Web2Sms\Client as W2SClient;

W2SClient::setConfig([
    'api_user_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
    'api_key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
]);

$message_id = 'e3f0bcd5-7742-433d-add2-11a00b89a477';

$response = W2SClient::getMessageStatus($message_id);
var_dump($response);