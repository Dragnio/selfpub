<?php


namespace app\helpers;

use TwitterAPIExchange;

/**
 * Class TwitterHelper
 *
 * @package app\helpers
 */
class TwitterHelper
{

    private static $twitter = false;

    /**
     * @return bool|TwitterAPIExchange
     */
    private static function getService()
    {
        if (!self::$twitter) {
            $settings = array(
                'oauth_access_token'        => "2537055691-ejrbV2EmkKffIEaHRLjWbJuwGaasmaTzgFZGFh1",
                'oauth_access_token_secret' => "qzO1Cp2ucYeJCX9VYXICgdn68yizFLFOMagjJyT8D1VQS",
                'consumer_key'              => "iR6QgI88GkL3px2eaibVNUOeD",
                'consumer_secret'           => "wJ5Thj4lck3eQf155V7fmHyRXqEAjGbOmTPcUR0aR7EJPllsVn"
            );
            self::$twitter = new TwitterAPIExchange($settings);
        }
        return self::$twitter;
    }

    public static function post($text)
    {
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $requestMethod = 'POST';

        self::getService()->setPostfields(['status' => $text])
            ->buildOauth($url, $requestMethod)
            ->performRequest();

    }


} 