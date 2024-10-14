# Web 2 SMS Client

PHP library for sending SMS in Cameroon using web2sms237 API. Visit [https://web2sms237.com](https://web2sms237.com "SMS Pro & Bulk SMS Cameroon") to create your account.

## Requirement

You need **php version >=5.5** to use this library

## Installation

```bash
composer require patrickmaken/web2sms-client
```

## Usage

Before any operation, you must initialize the client by giving it your api_user_id and api_user_secret. These values are available in the "API Deleloppers menu" of your customer panel on the platform [https://www.web2sms237.com/en/webapp/developers](https://www.web2sms237.com/en/webapp/developers).

### Send SMS

```php
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
```

Output:
```bash
array(2) {
  ["id"]=>
  string(36) "e3f0bcd5-7742-433d-add2-11a00b89a477"
  ["cost"]=>
  int(12)
}
```

### Get SMS Status

```php
use Patrickmaken\Web2Sms\Client as W2SClient;

W2SClient::setConfig([
    'api_user_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
    'api_key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
]);

$message_id = 'e3f0bcd5-7742-433d-add2-11a00b89a477';

$response = W2SClient::getMessageStatus($message_id);
var_dump($response);
```

Output:
```bash
array(2) {
  ["status"]=>
  string(4) "SENT"
  ["sent_by"]=>
  string(5) "MyApp"
}
```

## contacts
+ Email: [support@web2sms237.com](mailto:support@web2sms237.com)
+ Telephone / Whatsapp: [+237 674 35 29 69](https://wa.me/237674352969)
