<?php
namespace Fluent\Message\Factory;

use Fluent\Action;

class Create
{
    public static function from($template, $defaults)
    {
        $message = new Action\Create(
            $template->getContent(), $defaults
        );

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
