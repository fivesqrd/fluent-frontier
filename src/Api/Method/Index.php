<?php
namespace Fluent\Api\Method;

class Index
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
        $suffix = !empty($this->_params) ? '?' . http_build_query($this->_params) : null;
        
        return [
            CURLOPT_URL           => $this->_url . '/' . $suffix
        ];
    }
}