

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customsms {

    private $_CI;
    var $AUTH_KEY = ""; //your AUTH_KEY here
    var $senderId = ""; //your senderId here
    var $routeId = ""; //your routeId here
    var $smsContentType = ""; //your smsContentType here

    function __construct() {
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    }

    function sendSMS($to, $message) {

        $args = http_build_query(array(
            'token' => 'GlmBhQaWBDeTQK4CY47y',
            'from'  => 'Braindigit',
            'to'    => $to,
            'text'  => $message));

        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;




    }

}
?>