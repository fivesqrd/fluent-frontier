<?php
namespace Fluent\Api\Method;

class Get
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
            CURLOPT_URL => $this->_url . '/' . $this->_params['id']
        ];
    }
}