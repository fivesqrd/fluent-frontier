<?php
namespace Fluent\Message;

abstract class Template
{
    /**
     * @return mixed
     */
    public function getContent()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array();
    }
}
