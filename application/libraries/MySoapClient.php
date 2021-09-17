<?php

class MySoapClient extends SoapClient
{
    private $action;

    public function __construct($wsdl, $options = array(), $action = null)
    {
        if ($options['stream_context'] && is_resource($options['stream_context'])) {
            $stream_context_options = stream_context_get_options($options['stream_context']);
            $user_agent = (isset($stream_context_options['http']['user_agent']) ? $stream_context_options['http']['user_agent'] : "PHP-SOAP/" . PHP_VERSION) . "\r\n";
            if (isset($stream_context_options['http']['header'])) {
                if (is_string($stream_context_options['http']['header'])) {
                    $user_agent .= $stream_context_options['http']['header'] . "\r\n";
                } else if (is_array($stream_context_options['http']['header'])) {
                    $user_agent .= implode("\r\n", $stream_context_options['http']['header']);
                }
            }
            $options['user_agent'] = $user_agent;
        }
        $this->action = $action;
        parent::__construct($wsdl, $options);
    }

    function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: " . $this->action,
            "Content-length: " . strlen($request),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}