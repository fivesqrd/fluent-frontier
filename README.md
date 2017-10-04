Fluent for PHP
============
Programmatic approach to generating and sending responsive user notifications via e-mail

### Benefits ###
- Easy API to generate HTML based e-mail bodies in your app
- Less time wrestling with CSS inlining
- Automatically responsive

### Install ###
```
php composer.phar require fivesqrd/fluent:3.4
```

We provide a package for Laravel projects here: https://github.com/Five-Squared/Fluent-Laravel

### Quick Examples ###
Create and send a message:
```
$messageId = Fluent::message()->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->number(['caption' => 'Today', value => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
    ->subject('Testing it')
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

Sending with custom headers
```
$messageId = Fluent::message()->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->number(['caption' => 'Today', value => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
    ->teaser('This is a teaser')
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
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->subject('Testing it')
    ->attach('My-Attachment.pdf', 'application/pdf', file_get_contents($file))
    ->from('me@myapp.com', 'My App')
    ->to('user@theirdomain.com')
    ->send();
```

### Resending a message
Resend the original message. Optionally specify a different recipient.
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

### Other Use Cases ###
Somtimes you need to send plain text emails. Fluent provides a way to do this:
```
$messageId = Fluent::message()->create('This is my plain text message body')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

Sometimes you may want to create a completely custom HTML message:
```
$html = '<html><body><p>This is my plain text message body</p></html></body>';

$messageId = Fluent::message()->create(new Fluent\Content\Raw($html))
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```
