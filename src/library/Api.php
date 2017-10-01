<?php
namespace Fluent;

class Api 
{
    protected $_curl;
    
    protected $_key;
    
    protected $_secret;

    protected $_endpoint;
    
    protected $_debug = false;
    
    const ENDPOINT = 'https://fluent.5sq.io/service/v3';
    
    public function __construct($key, $secret, $endpoint = null, $debug = false)
    {
        $this->_curl = curl_init();
        $this->_key = $key;
        $this->_secret = $secret;
        $this->_endpoint = $endpoint;
        $this->_debug = $debug;
    }

    public function getEndpoint()
    {
        if ($this->_endpoint !== null) {
            return $this->_endpoint;
        }

        return self::ENDPOINT;
    }

    public function isDebugOn()
    {
        return ($this->_debug === true);
    }
    
    public function call($resource, $method, $params)
    {
        $url = $this->getEndpoint() . '/' . $resource;

        /* Determine the right method handler for this request */
        
        $handler = $this->_getMethodHandler($method, $url, $params);

        /* Set all the curl options */

        foreach ($this->_getCurlOptions($handler) as $key => $value) {
            
            curl_setopt($this->_curl, $key, $value);

        }

        /* Send the request and get a response */

        $response = $this->_getResponse($url);

        if ($response === null) {
            throw new Exception(
                'We were unable to decode the JSON response from the Fluent API: ' . json_encode($response)
            );
        }

        return $response;
    }

    protected function _getResponse($url)
    {
        $start = microtime(true);

        $this->_log('Call to ' . $url);

        if ($this->isDebugOn()) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($this->_curl, CURLOPT_STDERR, $curl_buffer);
        }
        
        $body = curl_exec($this->_curl);
        $info = curl_getinfo($this->_curl);
        $time = microtime(true) - $start;

        if ($this->isDebugOn()) {
            rewind($curl_buffer);
            $this->_log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        
        $this->_log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->_log('Got response: ' . $body);
        
        if (curl_error($this->_curl)) {
            throw new Exception(
                "API call to " . $url . " failed: " . curl_error($this->_curl)
            );
        }
        
        if (floor($info['http_code'] / 100) >= 4) {
            throw new Exception("{$info['http_code']}, " . $response->message);
        }

        return json_decode($body);
    }

    protected function _getCurlOptions($handler)
    {
        $options = [
            CURLOPT_VERBOSE        => $this->isDebugOn(),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->_key . ':' . $this->_secret,
            CURLOPT_USERAGENT      => 'Fluent-Library-PHP-v' . Factory::VERSION
        ];


        foreach ($handler->getOptions() as $key => $value) {
            $options[$key] = $value;
        }

        return $options;
    }

    protected function _getMethodHandler($method, $url, $params)
    {
        switch ($method) {
            case 'create':
                $handler = new Api\Method\Create($url, $params);
                break;
            case 'update':
                $handler = new Api\Method\Update($url, $params);
                break;
            case 'get':
                $handler = new Api\Method\Get($url, $params);
                break;
            case 'index':
                $handler = new Api\Method\Index($url, $params);
                break;
            default:
                $handler = new Api\Method\Rpc($url, $params, $method);
                break;
        }

        return $handler;
    }
    
    protected function _log($msg) 
    {
        if ($this->isDebugOn()) {
            error_log($msg);
        }
    }
}
