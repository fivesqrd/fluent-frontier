<?php
namespace Fluent;

class Event
{
    protected $_defaults = array();

    public function __construct($defaults)
    {
        $this->_defaults = $defaults;
    }

    /**
     * @param int $id
     * @return \Fluent\Event\Fetch
     */
    public function fetch($id)
    {
        return new Event\Fetch(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret']),
            $id
        );
    } 

    /**
     * @return \Fluent\Event\Find
     */
    public function find()
    {
        return new Event\Find(
            new \Fluent\Api($this->_defaults['key'], $this->_defaults['secret'])
        );
    } 
}
