<?php

define('FLUENT_PATH', realpath(__DIR__ . '/../library'));

require_once FLUENT_PATH . '/Factory.php';
require_once FLUENT_PATH . '/Api.php';
require_once FLUENT_PATH . '/Exception.php';
require_once FLUENT_PATH . '/Content.php';
require_once FLUENT_PATH . '/Content/Markup.php';
require_once FLUENT_PATH . '/Message.php';
require_once FLUENT_PATH . '/Message/Create.php';
require_once FLUENT_PATH . '/Transport.php';
require_once FLUENT_PATH . '/Transport/Remote.php';
require_once FLUENT_PATH . '/Transport/Local.php';
require_once FLUENT_PATH . '/Storage.php';
require_once FLUENT_PATH . '/Storage/Sqlite.php';

$defaults = array(
    //'key'      => '9fe630283b5a62833b04023c20e43915',
    //'secret'   => 'test',
    'key'       => '60ced422',
    'secret'    => '122f3a57615d1190752a6b7fcc60f901',
    'sender'   => array('name' => 'The Acme Company', 'address' => 'fluent@5sq.io'),
    'endpoint' => 'http://localhost/fluent/service/v3',
    //'endpoint' => 'https://fluent.clickapp.co.za/v3',
    'debug'    => true
);

$numbers = array(
    ['value' => '$95.00', 'caption' => 'Billed'], 
    ['value' => '$95.00', 'caption' => 'Paid'],
    ['value' => '$0.00', 'caption' => 'Balance']
);

try {
    $messageId = Fluent\Factory::message($defaults)->create()
        ->addParagraph('We have just processed your monthly payment for Musixmatch monthly subscription (10 Feb - 9 Mar).')
        ->addNumber($numbers)
        ->addButton('http://www.myinvoices.com', 'Download Invoice')
        ->addParagraph('Please note the transaction will reflect on your statement as <b>"Musixmatch"</b>. Please <a href="#">contact us</a> if you have any questions about this receipt or your account.')
        ->setTeaser('This is a test receipt teaser.')
        ->setTitle('Receipt')
        ->subject('Test E-mail Receipt')
        ->header('Reply-To', 'christianjburger@me.com')
        ->to('christianjburger@gmail.com')
        //->send(\Fluent\Transport::LOCAL);
        ->send(\Fluent\Transport::REMOTE);
    echo 'Sent message: ' . $messageId . "\n";
} catch (Fluent\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
