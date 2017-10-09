<?php
namespace Fluent;

/**
 *
 * @author cjb
 */
class Message
{
    protected $_defaults;

    public function __construct($defaults = array())
    {
        $this->_defaults = $defaults;
    }
    
    /**
     * @param mixed $content
     * @return \Fluent\Message\Create
     */
    public function create($content = null)
    {
        return new Message\Action\Create($content, $this->_defaults);
    }

    /**
     * @param mixed $params
     * @return string
     */
    public function send($template)
    {
        if (is_array($template)) {
            $template = new Message\Dto($template);
        }

        return Message\Factory\Create::from($template, $this->_defaults)->send();
    }

    /**
     * @param string $id
     * @return \Fluent\Message\Resend
     */
    public function resend($id)
    {
        return new Message\Action\Resend($id, $this->_defaults);
    }

    /**
     * @param string $id
     * @return \Fluent\Message\Fetch
     */
    public function fetch($id)
    {
        return new Message\Action\Fetch(
            new Api($this->_defaults['key'], $this->_defaults['secret']), $id
        );
    } 

    /**
     * @return \Fluent\Message\Find
     */
    public function find()
    {
        return new Message\Action\Find(
            new Api($this->_defaults['key'], $this->_defaults['secret'])
        );
    } 
}
