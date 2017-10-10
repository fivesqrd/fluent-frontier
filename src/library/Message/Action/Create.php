<?php
namespace Fluent\Message\Action;

/**
 *
 * @author cjb
 *
 * @method \Fluent\Message\Create title(string $text)
 * @method \Fluent\Message\Create paragraph(string $text)
 * @method \Fluent\Message\Create button(string $href, string $text)
 * @method \Fluent\Message\Create number(array $number)
 * @method \Fluent\Message\Create numbers(array $numbers)
 * @method \Fluent\Message\Create teaser(string $value)
 * @method \Fluent\Message\Create segment(string $value)
 */

use Fluent\Message\Content;
use Fluent\Message\Template;
use Fluent\Exception;

class Create
{
    /**
     * @var \Fluent\Content
     */
    protected $_content;
    
    protected $_sender = array('address' => null, 'name' => null);

    protected $_recipient = array('address' => null, 'name' => null);
    
    protected $_subject;
    
    protected $_options = array();

    protected $_headers = array();
    
    protected $_attachments = array();
    
    protected $_defaults = array();

    public function __construct($content, $defaults = array())
    {
        $this->_content = $this->_getContent($content);

        $this->_defaults = $defaults;
    }

    protected function _getContent($value)
    {
        if ($value instanceof Template) {
            return $value->getContent();
        } elseif ($value instanceof Content\Markup) {
            return $value;
        } elseif ($value instanceof Content\Raw) {
            return $value;
        } elseif ($value === null) {
            return new Content\Markup();
        } elseif (is_string($value) && strstr($value, '<content>')) {
            return new Content\Markup($value);
        } else {
            return new Content\Raw($value);
        }
    }
    
    protected function _getDefault($name, $fallback = null)
    {
        if (array_key_exists($name, $this->_defaults)) {
            return $this->_defaults[$name];
        }
        
        return $fallback;
    }
    
    public function __toString()
    {
        return $this->_content->toString();
    }
    
    /**
     * @param string $name
     * @param array $arguments
     * @throw \Fluent\Exception
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->_content, $name)) {
            $object = $this->_content;
        } else {
            throw new Exception('Invalid method ' . $name . ' for ' . get_class($this->_content));
        }
    
        call_user_func_array(array($object, $name), $arguments);
    
        return $this;
    }
    
    /**
     * Call Fluent Web Service and return a message ID
     * @return string $messageId
     */
    public function send()
    {
        $api = new \Fluent\Api(
            $this->_getDefault('key'), 
            $this->_getDefault('secret'), 
            $this->_getDefault('endpoint'), 
            $this->_getDefault('debug')
        );

        $response = $api->call('message', 'create', $this->toArray());

        return $response->_id;
    }
    
    
    /**
     * @return \Fluent\Message\Action\Create
     */
    public function subject($value)
    {
        $this->_subject = $value;
        return $this;
    }
    
    /**
     * @return \Fluent\Message\Action\Create
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
    
    /**
     * @param string $name
     * @param string $contentType
     * @param string $content
     * @return \Fluent\Message\Action\Create
     */
    public function attach($name, $type, $content)
    {
        array_push($this->_attachments, array(
            'name'      => $name,
            'type'      => $type,
            'content'   => base64_encode($content)
        ));

        return $this;
    }

    /**
     * @param array $values
     * @return \Fluent\Message\Action\Create
     */
    public function attachments(array $values)
    {
        foreach ($values as $attachment) {
            $this->attach($attachment['name'], $attachment['type'], $attachment['content']); 
        }
    
        return $this;
    }
    
    /**
     * @param string $address
     * @param string $name
     * @return \Fluent\Message\Action\Create
     */
    public function from($address, $name = null)
    {
        if (is_array($address)) {
            $this->_sender = $address;
        } else {
            $this->_sender = array(
                'address' => $address,
                'name'    => $name
            );
        }
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Fluent\Message\Action\Create
     */
    public function option($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }

    public function options($values)
    {
        $this->_options = array_merge($this->_options, $values);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Fluent\Message\Action\Create
     */
    public function header($name, $value)
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * 
     * @param array $values
     * @return \Fluent\Message\Action\Create
     */
    public function headers(array $values)
    {
        $this->_headers = array_merge($this->_headers, $values);
        return $this;
    }
    
    /**
     * @return array
     */
    public function getSender()
    {
        if (isset($this->_sender['address']) && !empty($this->_sender['address'])) {
            return array('address' => $this->_sender['address'], 'name' => $this->_sender['name']);
        }

        return $this->_getDefault('sender');
    }
    
    /**
     * @return \Fluent\Message\Action\Create
     */
    public function getContent()
    {
        return $this->_content;
    }
    
    public function getOptions()
    {
        $content = array(
            'format' => $this->_content->getFormat(),
            'teaser' => $this->_content->getTeaser()
        );
        
        return array_merge($this->_options, $content);
    }

    public function getHeaders()
    {
        if (!array_key_exists('headers', $this->_defaults) || !is_array($this->_defaults['headers'])) {
            return $this->_headers;
        }

        return array_merge($this->_defaults['headers'], $this->_headers);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sender'      => $this->getSender(),
            'subject'     => $this->_subject,
            'recipient'   => $this->_recipient,
            'content'     => $this->_content->toString(),
            'headers'     => $this->getHeaders(),
            'attachments' => $this->_attachments,
            'options'     => $this->getOptions(),
        );
    }
}
