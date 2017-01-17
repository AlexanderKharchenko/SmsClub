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