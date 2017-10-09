<?php
namespace Fluent\Message;

class Dto extends Template
{
    protected $_params = array(
        'content'     => null,
        'recipient'   => null,
        'sender'      => null,
        'subject'     => null,
        'attachments' => null,
        'headers'     => null
    );

    public function __construct($params)
    {
        $this->_params = $params;
    }

    protected function _getValue($key, $default)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return $default;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_getValue('content', null);
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->_getValue('recipient', null);
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->_getValue('sender', null);
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->_getValue('subject', null);
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->_getValue('attachments', array());
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_getValue('headers', array());
    }
}