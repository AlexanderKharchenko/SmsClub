<?php

namespace SmsClub;

/**
 * Class SmsClubException
 * @package SmsClub
 */
class SmsClubException extends \Exception {

    /**
     * @var array
     */
    protected $errors = [
        '100'   => 'Пустой токен - токен учетной записи пользователя (его можно найти в профиле);',
        '101'   => 'Пустой Username - логин учетной записи пользователя',
        '102'   => 'Пустой From - альфа-имя, от которого идет отправка (11 английских символов, цифры, пробел)',
        '103'   => 'Неправильный формат from. (до 11 английских символов, цифры, пробел)',
    ];


    /**
     * Exception constructor
     *
     * @param null|string $message Сообщения исключения
     * @param int $code Код исключения
     */
    public function __construct($message = null, $code = 0)
    {
        if (isset($this->errors[$code])) {
            $message = $this->errors[$code];
        }
        parent::__construct($message, $code);
    }

}