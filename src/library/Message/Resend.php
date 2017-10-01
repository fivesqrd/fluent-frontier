<?php
namespace Fluent\Message;

/**
 *
 * @author cjb
 *
 */

use Fluent\Content;
use Fluent\Transport;
use Fluent\Exception;

class Resend
{
    /**
     * @var \Fluent\Content
     */
    protected $_content;
    
    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_defaults = array();

    protected $_messageId;
    
    public function __construct($messageId, $defaults = array())
    {
        $this->_messageId = $messageId;
        $this->_defaults = $defaults;
    }
    
    protected function _getDefault($name, $fallback = null)
    {
        if (array_key_exists($name, $this->_defaults)) {
            return $this->_defaults[$name];
        }
        
        return $fallback;
    }
    
    /**
     * @param string $transport
     */
    public function send()
    {
        
        $api = new \Fluent\Api(
            $this->_getDefault('key'), 
            $this->_getDefault('secret'), 
            $this->_getDefault('endpoint'), 
            $this->_getDefault('debug')
        );

        $params = array(
            'id'          => $this->_messageId,
            'recipient'   => isset($this->_recipient['address']) ? $this->_recipient['address'] : null,
        );
        
        $response = $api->call('message', 'update', $params);
        
        return $response->_id;
    }
    
    /**
     * @return \Fluent\Message
     */
    public function to($address, $name = null)
    {
        if (is_array($address)) {
            $this->_recipient = $address;
        } else {
            $this->_recipient = array(
                'address' => $address,
                'name'    => $name
            );
        }
        return $this;
    }
}
