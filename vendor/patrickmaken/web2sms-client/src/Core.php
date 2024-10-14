<?php

namespace Patrickmaken\Web2Sms;

use GuzzleHttp\Client;

class Core 
{
    protected const URL_GET_ACCESS_TOKEN = '/token';
    protected const URL_GET_MESSAGE_STATUS = '/sms/%s';
    protected const URL_SEND_SMS = '/sms/send';


    /**
     * @var string
     */
    protected static $apiUserId;

    /**
     * @var string
     */
    protected static $apiKey;

    /**
     * @var string
     */
    protected static $accessToken;

    /**
     * @param string $messageId
     * @throws \Exception
     * @return array
     */
    public static function getMessageStatus(string $messageId)
    {
        $url = sprintf(self::getUrl('GET_MESSAGE_STATUS'), $messageId);
        $headers = [
            'Authorization' => self::bearerToken()
        ];

        $client = self::makeClient(compact('headers'));
        return self::perform($client, $url, 'get', true, true);
    }

    /**
     * @param string $telephone
     * @param string $text
     * @param bool $isFlash
     * @param string|null $senderId
     * @throws \Exception
     * @return array
     */
    public static function sendSMS(string $telephone, string $text, bool $isFlash, $senderId)
    {
        $url = self::getUrl('SEND_SMS');
        $headers = [
            'Authorization' => self::bearerToken()
        ];
        $json = [
            'phone' => $telephone,
            'text' => $text,
            'flash' => $isFlash,
        ];
        if ($senderId) $json['sender_id'] = $senderId;

        $client = self::makeClient(compact('headers', 'json'));
        return self::perform($client, $url, 'post', true, true);
    }

    /**
     * @throws \Exception
     * @return array
     */
    public static function getAccessToken()
    {
        $url = self::getUrl('GET_ACCESS_TOKEN');
        $headers = [
            'Authorization' => self::basicAuth()
        ];

        $client = self::makeClient(compact('headers'));
        return self::perform($client, $url, 'post', true, true);
    }

    /**
     * @return string
     */
    protected static function basicAuth()
    {
        return  'Basic ' . base64_encode(self::$apiUserId . ':' . self::$apiKey);
    }

    /**
     * @return string
     */
    protected static function bearerToken()
    {
        return  'Bearer ' . self::$accessToken;
    }

    /**
     * @throws \Exception
     * @return array|null|string
     */
    private static function perform(Client $client, string $url, string $method, $shouldThrowException = false, $jsonResponse = true)
    {
        try {
            $response = $client->$method($url);
            $response = (string)$response->getBody();
            if ($jsonResponse) $response = json_decode($response, true);
        } catch (\Throwable $th) {
            if ($shouldThrowException) throw $th;
            return null;
        }

        return $response;
    }


    /**
     * @param string $key
     * @return string
     */
    private static function getUrl(string $key)
    {
        $url = trim(file_get_contents(dirname(__FILE__, 2) . '/base_url'));
        switch ($key) {
            case 'GET_ACCESS_TOKEN':
                $url .= self::URL_GET_ACCESS_TOKEN;
            break;
            case 'SEND_SMS':
                $url .= self::URL_SEND_SMS;
            break;
            case 'GET_MESSAGE_STATUS':
                $url .= self::URL_GET_MESSAGE_STATUS;
            break;
        }
        return $url;
    }

    private static function makeClient(array $params = [])
    {
        $options = [
            'timeout'  => 20.0,
            'verify' => false,
        ];
        foreach ($params as $key => $value) $options[$key] = $value;

        return new Client($options);
    }

    
    public static function setApiUserId(string $apiUserId)
    {
        self::$apiUserId = $apiUserId;
    }

    public static function setApiKey(string $apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function setAccessToken(string $accessToken)
    {
        self::$accessToken = $accessToken;
    }

}