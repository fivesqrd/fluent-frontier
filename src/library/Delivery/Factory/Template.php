<?php
namespace Fluent\Message\Factory;

use Fluent\Action;

class Template
{
    public function __construct($template)
    {

    }


    public static function create($defaults)
    {
        $message = new Action\Create($defaults);

        if ($template->getContent()) {
            $message->content($template->getContent());
        }

        if ($template->getSubject()) {
            $message->subject($template->getSubject());
        }

        if ($template->getRecipient()) {
            $message->to($template->getRecipient());
        }

        if ($template->getSender()) {
            $message->from($template->getSender());
        }

        if ($template->getAttachments()) {
            $message->attachments($template->getAttachments());
        }

        if ($template->getHeaders()) {
            $message->headers($template->getHeaders());
        }

        return $message;
    }
}
