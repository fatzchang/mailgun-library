# mailgun-library

## Description
sending/validating email via [Mailgun](https://www.mailgun.com/)

## usage
```php
$mailgun = new Mailgun([
    'domain' => 'example.com',
    'privateKey' => 'mailgun_private_key',
    'publicKey' => 'mailgun_public_key',
    'sender' => 'name of sender',
    'service' => 'email of sender'
]);
```
### send email:
```php
$result = $mailgun->send($mail, $subject, $content);
```

### validate email:
```php
$result = $mailgun->validate($mail);
```