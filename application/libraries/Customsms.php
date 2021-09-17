<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customsms
{

    private $_CI;

    protected $name;
    protected $authkey;
    protected $url;
    var $AUTH_KEY = ""; //your AUTH_KEY here
    var $senderId = ""; //your senderId here
    var $routeId = ""; //your routeId here
    var $smsContentType = ""; //your smsContentType here

    function __construct($array)
    {
        //initialize the CI super-object
        $this->_ci = &get_instance();


        //get settings from config
        $this->username = $array['username'];
        $this->password = $array['password'];
        $this->from = $array['name'];
        $this->url = $array['url'];

    }

    function sendSMS($to, $message)
    {
        $params = 'username=' . urlencode($this->username);
        $params .= '&password=' . urlencode($this->password);
        $params .= '&to=' . urlencode($to);
        $params .= '&content=' . urlencode($message);
        $url = $this->url . '?' . $params;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $error = false;
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch) == 0 || $status_code !== 200) {
            $error = curl_error($ch);
        }
        curl_close($ch);
        $ret = array(
            'error' => $error,
            'response' => $response,
        );
        log_message('error', "SMS response " . print_r($ret, true));
        return $ret;
    }

}