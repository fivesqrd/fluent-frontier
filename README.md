Fluent for PHP
============
The PHP client for the Fluent Web Service. Fluent is a simple and elegant way to create and send transactional emails from your web or mobile app.


## Install ##
To get Fluent for PHP installed simply pull in the composer package:

```
composer require fivesqrd/fluent:4.*
```

For Laravel projects there is an easy to install package available
```
composer require fivesqrd/fluent-laravel
```

More info on the Laravel config options and the Fluent facade is available here: https://github.com/fivesqrd/fluent-laravel

### Benefits ##
- Provides consistency between development, staging and production environments
- Safe email testing sandbox that won't spam your users
- Email logs with 60 day content retention period
- Less wrestling with tables and CSS inlining using [Jit](http://fluentmsg.com/jit)

## Register
To send messages you'll first need to [register](http://fluentmsg.com) Fluent account. Once registered, you'll receive API key to
start sending messages immediately.


## Instantiating Objects ##

Passing config to the Message object at run time:
```
$config = [
    'key'           => null, // Fluent access key
    'secret'        => null, // Fluent access secret
    'headers'       => null, // Optional default sender
];

$delivery = (new Fluent\Delivery($config))->create();
$events = (new Fluent\Event($config))->find();
```

To make bootstrapping eaiser, we've provided a way to preload config by assigning options to the static $defaults property of the Factory class:
```
Fluent\Factory::$defaults = [
    'key'           => null, // Fluent access key
    'secret'        => null, // Fluent access secret
    'headers'       => null, // Optional default sender
];

$message = Fluent\Factory::message()->create();
$events = Fluent\Factory::event()->create();

```


## Message Delivery
One can easily deliver the message by combining the message body with the Fluent Web Service client.

```
$body = (new Fluent\Body())
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->number(['caption' => 'Today', 'value' => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames.');

$messageId = (new Fluent\Delivery())->create()
    ->content($body)
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

The following methods are provided to set up the delivery of the message:

### subject($text)
```
/* Add a subject to the message */
$message->subject('Lorem ipsum dolor');
```

### header($key, $value) or headers($values)
```
/* Add a header to the message */
$message->header('Reply-To', 'me@myapp.com');
```

```
/* Add multiple headers to the message */
$message->headers(array(
    'Reply-To', 'me@myapp.com',
    'X-Fluent', 'lorem'
));
```

### from($address, $name = null)
```
/* Set the sender address and name */
$message->from('me@myapp.com', 'My App');
```
    
### to($address, $name = null)
Note: only one recipient can be provided per message.
```
/* Set the recipient address and name */
$message->to('user@theirdomain.com');
```

### attach($filename, $mimetype, $blob) or attachments($values)
```
/* Add an attachment to the message */
$message->attach('My-Attachment.pdf', 'application/pdf', file_get_contents($file))
```

```
/* Add multiple attachments to the message */
$message->attachments(array(
    ['name' => 'My-First-File.pdf', 'type' => 'application/pdf', 'content' => file_get_contents($file)],
    ['name' => 'My-2nd-File.jpg', 'type' => 'image/jpg', 'content' => file_get_contents($file2)],
));
```

### send()
Send is the final method of the chain and should always be called last. It delivers to message to the Fluent Web Service and returns a unique message ID.
```
/* Send the message */
$messageId = $message->send();
```

## Templates
It's possible to simplify the body construction and message delivery with pre-built template classes:

```
/* Double action: construct and send */
$template = new MyApp\Template\PasswordReset($params);

$messageId = (new Fluent\Delivery())->from($template)->send();
```


## Additional Features ##

Sending with custom headers
```
$messageId = (new Fluent\Delivery())->create()
    ->content($body)
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send(); 
```

Sending with attachments
```
$messageId = (new Fluent\Delivery())->create()
    ->content($body)
    ->subject('Testing it')
    ->attach('My-Attachment.pdf', 'application/pdf', file_get_contents($file))
    ->to('user@theirdomain.com')
    ->send();
```


## More Web Service Features

### Resending a message
In most cases resending an email notification is hard because it has to be recreated again, but the application state has since changed. With Fluent it is possible to simply resend a snapshot of the original message. Optionally specify a different recipient.
```
$response = (new Fluent\Delivery())->resend($messageId)
    ->to('other@theirdomain.com')
    ->send();
```

### Retrieving a sent message
```
$response = (new Fluent\Message())->get($messageId)->fetch();
```

### Searching sent messages
```
$response = (new Fluent\Message())->find()
    ->from('me@myapp.com')
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', strtotime('-2 days ago')))
    ->fetch();
```

### Find events
Get delivery progress updates for a particular recipient address
```
$response = (new Fluent\Event())->find()
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', strtotime('-2 days ago')))
    ->type(['hard_bounce', 'soft_bounce', 'reject'])
    ->fetch();
```

## Alternative Formats
Somtimes you need to send plain text or custom HTML emails. Fluent provides a way to do this:

### Plain Text ###
```
$messageId = (new Fluent\Message())->create('This is my plain text message body')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

### Custom HTML ###
```
$html = '<html><body><p>This is my custom HTML message body</p></html></body>';

$messageId = (new Fluent\Message())->create(new Fluent\Message\Content\Raw($html))
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

## Generate HTML locally
Instead of delivering the message it is possible to render a HTML document locally. For this we 
used one of our open source email designs called Musimal and wrapped it inside a PHP library. It accepts
a Body object as argument and returns a responsive HTML email body as a string. 

```
# add the open source HTML wrapper to the project
composer require fivesqrd/fluent-musimal
```

Generate a responsive HTML string from the body object:

```
/* Predefine the HTML rendering options */
$options = array(
    'logo'          => 'My App', //Plain text or publicly available URL. Image not wider than 200px
    'color'         => '#ff6600', //Primary colour
    'footer'        => 'My test generated by Musimal for PHP', //Text at bottom of all messages
);

/* Construct a message body */
$body = (new Fluent\Body())
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->number(['caption' => 'Today', 'value' => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames.');

/* Render the body to an HTML document */
$html = (new Fluent\Layout($options))->render($body);
```
