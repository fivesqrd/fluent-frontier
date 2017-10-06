Fluent for PHP
============
Wrapper of pre-built responsive email components. Great for user notifications like password resets, user welcomes, receipts, etc.

### Benefits ###
- Easy API to generate HTML based e-mail bodies in your app
- Less time wrestling with CSS inlining
- Responsive out of the box

![alt text](https://github.com/Five-Squared/Fluent-Library-PHP/blob/3.4/mockups/Responsive-Email-On-Apple-Devices.png "Responsive e-mail layout")

### Live Demo ###

To see a real sample in your inbox, head on over to http://fluentmsg.com and send a test email to yourself.

### UI Compents ###
Fluent provides a single column responsive email layout with support for several types of UI components. By combining the various UI components together, one can easily generate many of the most common types of user notifications needed for a project. Each component occupies the full width of the layout and is stacked on top of each other. 


The current supported UI components are:
1. Teaser - a short piece of text displayed on the list of view of most email clients
2. Logo - image displayed at the top of a message
3. Title - heading inside the message body
4. Paragraphs - sections of text seperated by decent helping of space
5. Numbers - highlight a numeric value with a caption
6. Buttons - call to action button
7. Segments - custom HTML to be displayed
8. Footer - a line of text at the bottom for an address, opt out, etc

### Sample Email Layout ###
Below is a sample of an email layout that uses some of the UI components
![alt text](https://github.com/Five-Squared/Fluent-Library-PHP/blob/3.4/mockups/Layout-640x960.png "Responsive e-mail layout")

### Install ###
```
php composer.phar require fivesqrd/fluent:3.4
```

For Laravel projects there is an easy to install package available here: https://github.com/Five-Squared/Fluent-Laravel


### Quick Examples ###
Create and send a message:
```
$messageId = Fluent::message()->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->number(['caption' => 'Today', value => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames.')
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

Sending with custom headers
```
$messageId = Fluent::message()->create()
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
$messageId = Fluent::message()->create()
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
$messageId = Fluent::message()->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
    ->segment('<table><tr><td>23 May</td><td>Concert at the park</td><td align="right">$30.00</td></tr></table>')
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

### Use Cases ###

#### Receipt Notification ####
```
$messageId = Fluent::message()->create()
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
#### User Welcome ####
```
$messageId = Fluent::message()->create()
    ->title('Welcome')
    ->paragraph('Many thanks for you signing up for a trial of Click Science. We're happy to have you.')
    ->paragraph('To get started be sure to check out your account by logging into the admin console.')
    ->button('http://www.fluentmsg.com', 'Admin Console')
    ->paragraph('If you need anything at all just reply to this email and we\'ll be happy to help.')
    ->teaser('Thanks for you signing up')
    ->subject('Welcome to Click Science')
    ->header('Reply-To', 'support@myproject.com')
    ->to('user@theirdomain.com')
    ->send();
```
#### Password Reset ####
```
$messageId = Fluent::message()->create()
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
### Resending a message
In most cases resending an email notification is hard because it has to be recreated again, but the application state has since changed. With Fluent it is possible to simply resend a snapshot of the original message. Optionally specify a different recipient.
```
$response = Fluent::message()->resend($messageId)
    ->to('other@theirdomain.com')
    ->send();
```

### Retrieving a sent message
```
$response = Fluent::message()->get($messageId)->fetch();
```

### Searching sent messages
```
$response = Fluent::message()->find()
    ->from('me@myapp.com')
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', strtotime('-2 days ago')))
    ->fetch();
```

### Find events
Get delivery progress updates for a particular recipient address
```
$response = Fluent::event()->find()
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', strtotime('-2 days ago')))
    ->type(['hard_bounce', 'soft_bounce', 'reject'])
    ->fetch();
```

### Plain Text ###
Somtimes you need to send plain text emails. Fluent provides a way to do this:
```
$messageId = Fluent::message()->create('This is my plain text message body')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

### Custom HTML ###
Sometimes you may want to create a completely custom HTML message:
```
$html = '<html><body><p>This is my plain text message body</p></html></body>';

$messageId = Fluent::message()->create(new Fluent\Content\Raw($html))
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```
