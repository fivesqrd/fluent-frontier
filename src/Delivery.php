<?php
namespace Fluent;

/**
 *
 * @author cjb
 */
class Delivery
{
    protected $_defaults;

    public function __construct($defaults = array())
    {
        $this->_defaults = $defaults;
    }
    
    /**
     * @param mixed $template
     * @return \Fluent\Message\Create
     */
    public function create($template = null)
    {
        if ($template) {
            return (new Message\Factory\Template($template))->create($this->_defaults);
        }

        return new Message\Action\Create($this->_defaults);
    }

    /**
     * @param mixed $template
     * @return \Fluent\Message\Create
     */
    public function from(Fluent\Template $object)
    {
        return (new Delivery\Factory\Template($template))->create($this->_defaults);
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
