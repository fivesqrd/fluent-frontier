Fluent for PHP
============
Fluent generates beautiful and responsive transactional email notifications quickly with minimal markup. It's great for user notifications like password resets, user welcomes, receipts, shipping notifications, etc.

![alt text](https://github.com/fivesqrd/fluent-client-php/blob/4.0/mockups/Responsive-Email-On-Apple-Devices.png "Responsive e-mail layout")

## Benefits ##
- Easy API to generate HTML based e-mail bodies in your app
- Cleaner code base with less markup and no views required
- Less time wrestling with tables and CSS inlining
- Responsive out of the box
- Provides consistency between development, staging and production environments
- Safe email testing sandbox that won't spam your users
- Email logs with 60 day content retention period
- Business friendly license

## Live Demo ##

To see a real sample in your inbox, head on over to http://fluentmsg.com and send a test email to yourself.


## How it works ##
At the foundation is a single column responsive layout with support for several types of UI components. Each UI component occupies the full width of the layout. The message body is built up by stacking one component on top of another. By combining several UI components together, one can easily generate many of the most common types of user notifications needed in a web or mobile app. 


## UI Compents ##

The current supported UI components are:
1. Teaser - a short piece of text displayed on the list of view of most email clients
2. Logo - image displayed at the top of a message
3. Title - heading inside the message body
4. Paragraphs - sections of text seperated by decent helping of space
5. Numbers - highlight a numeric value with a caption
6. Buttons - call to action button
7. Segments - custom HTML to be displayed
8. Footer - a line of text at the bottom for an address, opt out, etc


## Sample Email Layout ##
Below is a sample of an email body that uses many of the UI components list above:

![alt text](https://github.com/fivesqrd/fluent-client-php/blob/4.0/mockups/Layout-640x960.png "Responsive e-mail layout")


## Install ##
To make use of Fluent Web Service, you'll need a [Fluent](http://fluentmsg.com) account and the Fluent Client:

```
composer require fivesqrd/fluent:4.*
```

For Laravel projects there is an easy to install package available
```
composer require fivesqrd/fluent-laravel
```

More info on the Laravel config options and the Fluent facade is available here: https://github.com/fivesqrd/fluent-laravel


## Instantiating Objects ##

Passing config to the Message object at run time:
```
$config = [
    'key'           => null, // Fluent access key
    'secret'        => null, // Fluent access secret
    'sender'        => null, // Optional default sender
    'headers'       => null, // Optional default sender
];

$message = (new Fluent\Message($config))->create();
$events = (new Fluent\Event($config))->find();
```

It is possible to preload config at startup using the Factory class:
```
Fluent\Factory::$defaults = [
    'key'           => null, // Fluent access key
    'secret'        => null, // Fluent access secret
    'sender'        => null, // Optional default sender
    'headers'       => null, // Optional default sender
];

$message = Fluent\Factory::message()->create();
$events = Fluent\Factory::event()->create();

```

## Message Creation Methods ##
For creating messages there is a method available for each UI component listed above. Some UI components exists only ones and can only be set once, 
others can be called multiple times to build up the message body:

### teaser($text)
Set the text visible in previews but hidden when the message is opened. A message can only have one teaser.
```
/* Set the teaser of the message */
$message->teaser('Your receipt details inside');
```

### title($text)
Set the title of the message body. A message can only have one title. Limit to one or two words as the title is presented in quite a large font size.
```
/* Set the title of the message */
$message->title('Receipt');
```

### paragraph($text)
Add a paragraph block to the stack. The input value can contain HTML elements.
```
/* Add a paragraph of text to the message body */
$message->paragraph('Lorem <a href="#">ipsum dolor</a> sit amet, <b>consectetur</b> adipiscing elit.');
```

### number($value) or numbers($values)
Add a number block to the stack. A number consists of a value and an optional caption.

```
/* Add a number to the message body */
$message->number('$95.00');
```

```
/* Add a number/caption pair to the message body */
$message->number(['value' => '$95.00', 'caption' => 'Total']);
```

A number block supports up to 3 number columns. Multiple numbers can be passed as a multi dimensional array:
```
/* Add a row of 3 number columns to the message body */
$message->numbers(array(
    ['value' => '$95.00', 'caption' => 'Sub Total'],
    ['value' => '$9.50', 'caption' => 'Tax'],
    ['value' => '$104.50', 'caption' => 'Total'],
));
```

### button($url, $text)
Add a call to action button block to the stack. A button consists of a URL and the call to action text. 
```
/* Add a call to action button to the message body */
$message->button('http://myap.com/download/key', 'Download Invoice');
```

### segment($text)
Add a custom HTML segment block to the stack.
```
/* Add a custom HTML block to the message body */
$message->segment('<table><tr><td></td></tr></table>');
```

Note: the logo and footer components are configured from the [Fluent Admin Console](http://fluentmsg.com).


In addition to the UI methods, the following methods are provided to set up the delivery of the message at the same time:

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

## Examples ##
Double action: Create and send 
```
$messageId = (new Fluent\Message())->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->send([
        'subject'   => 'Testing it', 
        'recipient' => 'user@theirdomain.com', 
        'sender'    => 'me@myapp.com'
    ]);
```

Create and send expressively
```
$messageId = (new Fluent\Message())->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send(); ;
```

## Delivering pre-built content ##
In some cases one may need to seperate the message creation from the delivery. The send() method 
as shortcut to deliver aready built message content: 
```
$messageId = (new Fluent\Message())->send([
    'content'   => $content,
    'subject'   => 'Testing it', 
    'recipient' => 'user@theirdomain.com', 
    'sender'    => 'me@myapp.com'
]); 
```


## Additional Features ##

Sending with custom headers
```
$messageId = (new Fluent\Message())->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send(); 
```

Sending with attachments
```
$messageId = (new Fluent\Message())->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->subject('Testing it')
    ->attach('My-Attachment.pdf', 'application/pdf', file_get_contents($file))
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

Added segments of custom HTML
```
$messageId = (new Fluent\Message())->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->segment('<table><tr><td>23 May</td><td>Concert at the park</td><td align="right">$30.00</td></tr></table>')
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

## Real World Samples ##

### Receipt Notification ###
```
$messageId = (new Fluent\Message())->create()
    ->title('Receipt')
    ->paragraph('We have just processed payment of your monthly subscription.')
    ->number(['value' => '$95.00', 'caption' => 'Total'])
    ->button('http://www.fluentmsg.com', 'Download Invoice')
    ->paragraph('Please contact us if you have <b>any questions</b> about your account.')
    ->teaser('This message contains your receipt')
    ->subject('Receipt')
    ->header('Reply-To', 'me@myproject.com')
    ->to('user@theirdomain.com')
    ->send();
```

### User Welcome ###
```
$messageId = (new Fluent\Message())->create()
    ->title('Welcome')
    ->paragraph('Many thanks for you signing up for a trial of Click Science. We\'re happy to have you.')
    ->paragraph('To get started be sure to check out your account by logging into the admin console.')
    ->button('http://www.fluentmsg.com', 'Admin Console')
    ->paragraph('If you need anything at all just reply to this email and we\'ll be happy to help.')
    ->teaser('Thanks for you signing up')
    ->subject('Welcome to Click Science')
    ->header('Reply-To', 'support@myproject.com')
    ->to('user@theirdomain.com')
    ->send();
```

### Password Reset ###
```
$messageId = (new Fluent\Message())->create()
    ->title('Password Reset')
    ->paragraph('We have received a request to change the password for your Click Science account. If you requested this change, please follow the link below to reset your password.')
    ->button($href, 'Reset Password')
    ->paragraph('If you did not send this request, you may safely ignore this message and your password will remain unchanged.');
    ->teaser('Instructions for resetting your password')
    ->subject('Password reset requested')
    ->header('Reply-To', 'support@myproject.com')
    ->to('user@theirdomain.com')
    ->send();
```


## More Web Service Features

### Resending a message
In most cases resending an email notification is hard because it has to be recreated again, but the application state has since changed. With Fluent it is possible to simply resend a snapshot of the original message. Optionally specify a different recipient.
```
$response = (new Fluent\Message())->resend($messageId)
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