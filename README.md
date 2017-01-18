Клиент для работы с API сервиса [https://smsclub.mobi](https://smsclub.mobi/)

[![Total Downloads](https://poser.pugx.org/alexandr-kharchenko/sms-club/downloads?format=flat-square)](https://packagist.org/packages/alexandr-kharchenko/sms-club)
[![License](https://poser.pugx.org/alexandr-kharchenko/sms-club/license?format=flat-square)](https://packagist.org/packages/alexandr-kharchenko/sms-club)
[![Monthly Downloads](https://poser.pugx.org/alexandr-kharchenko/sms-club/d/monthly?format=flat-square)](https://packagist.org/packages/alexandr-kharchenko/sms-club)

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

# Быстрый старт
Создать клиеент.
```php
    $client = new \SmsClub\Client([
      'token'       => 'token',
      'username'    => 'username',
      'from'        => 'from'
    ]);
```

## Отправка сообщений
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
$response - при успешной отправке вернет массив 

```php
   [
      'response' => 1,
      'ids'      => ['id1' , 'id2', 'id3'], // ID отправленных сообщений
   ];
```

$response - при неудачной отправке вернет массив 
```php
   [
      'response' => 0,
      'error'      => 'Error message', // Текст ошибки API
   ];
```

## Проверить статус сообщений

```php
   $response =  $client->getStatus(['380955551122' , '2', '3']);
   
           
```

$response - при успешном запросе
```php
   [
      'response' => 1,
      'ids'      => ['key' =>
                        [
                            'id'     => 11,
                            'status' => Status,
                        ] , 
                        ...
                    ], 
   ];
```

$response - при неудаче

```php
   [
      'response' => 0,
      'error'      => 'Error message', // Текст ошибки API
   ];
```

## Проверить баланс пользователя
```php
    $balance = $client->getBalance()
 ```
 $balance -> 
 
 ```php
    [
            'balance'  => summ,
            'credit'   => summ,
            'currency' => currency, // - Не реализовано еще, всегда будет Not found
     ];
 ```
