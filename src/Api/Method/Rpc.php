<?php
namespace Fluent\Api\Method;

class Rpc
{
    protected $_params;

    protected $_url;

    protected $_method;

    public function __construct($url, $params, $method)
    {
        $this->_url = $url;
        $this->_params = $params;
        $this->_method = $method;
    }

    public function getOptions()
    {
        if (!array_key_exists('id', $this->_params)) {
            throw new Exception('id missing from REST RPC call');
        }
        
        return [
            CURLOPT_URL => $this->_url . '/' . $this->_params['id'] . '/' . $this->_method
        ];
    }
}