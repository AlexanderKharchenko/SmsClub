Клиент для работы с API сервиса [smsclub](https://smsclub.mobi/)

## Установка

### Через composer:

```bash
$ composer require alexandr-kharchenko/sms-club
```

или добавить

```json
"require" : {
    "alexandr-kharchenko/sms-club": "dev-master"
}
```

## Быстрый старт
Создать клиеент.
```php
    $client = new \SmsClub\Client();
```
### Установить получателей

Первый вариант, если нужен только один получатейль.

```php
    $client->setRecipient('380955551122');
```

Второй вариант, если нужно отправиль N получателям.

```php
    $client->setRecipient(['380955551122' , '2', '3']);
```

### Установить сообщение
```php
    $client->setRecipient(['380955551122' , '2', '3'])
            ->setMessage('Ваше сообщение');
```

### Отправить
```php
   $response =  $client->setRecipient(['380955551122' , '2', '3'])
            ->setMessage('Ваше сообщение')
            ->send();
```
