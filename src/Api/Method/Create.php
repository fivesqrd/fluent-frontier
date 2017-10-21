<?php
namespace Fluent\Api\Method;

class Create
{
    protected $_params;

    protected $_url;

    public function __construct($url, $params)
    {
        $this->_url = $url;
        $this->_params = $params;
    }

    public function getOptions()
    {
        return [
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => json_encode($this->_params),
            CURLOPT_POST       => true,
            CURLOPT_URL        => $this->_url
        ];
    }
}