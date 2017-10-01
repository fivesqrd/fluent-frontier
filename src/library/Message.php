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
        return new Message\Create($content, $this->_defaults);
    }

    /**
     * @param string $id
     * @return \Fluent\Message\Resend
     */
    public function resend($id)
    {
        return new Message\Resend($id, $this->_defaults);
    }

    /**
     * @param string $id
     * @return \Fluent\Message\Get
     */
    public function get($id)
    {
        return new Message\Get(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret']), $id
        );
    } 

    /**
     * @return \Fluent\Message\Find
     */
    public function find()
    {
        return new Message\Find(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret'])
        );
    } 
}
