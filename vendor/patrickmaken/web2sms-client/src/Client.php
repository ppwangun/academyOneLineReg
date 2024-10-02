<?php

namespace Patrickmaken\Web2Sms;

class Client
{
    /**
     * @param string $messageId
     * @throws \Exception
     * @return array
     */
    public static function getMessageStatus(string $messageId)
    {
        self::setConfig([
            'access_token' => self::getAccessToken()['access_token'],
        ]);
        return Core::getMessageStatus($messageId);
    }

    /**
     * @param string $telephone
     * @param string $text
     * @param string|null $senderId
     * @param string $isFlash
     * @throws \Exception
     * @return array
     */
    public static function sendSMS(string $telephone, string $text, string $senderId = null, bool $isFlash = false)
    {
        self::setConfig([
            'access_token' => self::getAccessToken()['access_token'],
        ]);
        return Core::sendSMS($telephone, $text, $isFlash, $senderId);
    }

    /**
     * @throws \Exception
     * @return array
     */
    public static function getAccessToken()
    {
        return Core::getAccessToken();
    }

    /**
     * @param array $configs
     * @return void
     */
    public static function setConfig (array $configs) 
    {
        if (array_key_exists('api_user_id', $configs)) Core::setApiUserId($configs['api_user_id']);
        if (array_key_exists('api_key', $configs)) Core::setApiKey($configs['api_key']);
        if (array_key_exists('access_token', $configs)) Core::setAccessToken($configs['access_token']);
    }
}