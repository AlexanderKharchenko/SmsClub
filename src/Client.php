<?php

namespace SmsClub;

class Client
{

    /**
     * Адрес службы
     */
    const API_URL = "https://gate.smsclub.mobi/token/";
    /**
     * Токен учетной записи пользователя (который можно найти в профиле пользователя);
     */
    const TOKEN = "";
    /**
     * логин учетной записи пользователя;
     */
    const USERNAME = "";
    /**
     * Альфа-имя, от которого идет отправка (11 английских символов, цифры, пробел);
     */
    const FROM = "";
    /**
     * Шаблон
     */
    const PATTERN_RESPONSE = "#=IDS START=(.+?)=IDS END=#is";

    /**
     * Кодировка сообщения по умолчанию
     * @var string
     */
    public $charset = 'UTF-8';


    /**
     * Массив, получателей.
     * @var array
     */
    public $recipients = [];


    /**
     * Тест сообщения
     * @var
     */
    public $message;


    /**
     * smsclub constructor.
     *
     */
    public function __construct()
    {

    }


    /**
     * @param $recipients
     *
     * @return $this
     */
    public function setRecipient($recipients)
    {
        if (is_array($recipients)) {
            foreach ($recipients as $val) {
                array_push($this->recipients, $val);
            }

        } else
            array_push($this->recipients, $recipients);

        return $this;
    }


    /**
     * Устанавливает текст сообщения
     *
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = urlencode(trim($message));

        return $this;
    }


    /**
     * Отправляет сообщение
     * @return array
     */
    public function send()
    {
        if (empty(trim($this->message)))
            return ['error' => 'Message is empty'];

        $message = iconv('utf-8', 'windows-1251', $this->message);
        $requestParams = [
            'username' => self::USERNAME,
            'token'    => self::TOKEN,
            'from'     => self::FROM,
            'to'       => implode(';', $this->recipients),
            'text'     => $message,
        ];
        $response = $this->_request('', $requestParams);

        return $this->response($response);
    }


    /**
     * Запрашивает статус сообщений
     *
     * @param array $smsIds
     *
     * @return array
     */
    public function getStatus(array $smsIds)
    {
        $requestParams = [
            'username' => self::USERNAME,
            'token'    => self::TOKEN,
            'smscid'   => implode(';', $smsIds),

        ];

        $response = $this->_request('state.php', $requestParams);

        return $this->response($response, 'status');
    }


    /** Возвращает состояние баланса
     * @return array
     */
    public function getBalance()
    {
        $requestParams = [
            'username' => self::USERNAME,
            'token'    => self::TOKEN,
        ];

        // TODO: preg_match_all - определить валюту
        $response = $this->_request('getbalance.php', $requestParams);
        preg_match_all("/[0-9]+[.][0-9]{4}/", $response, $balanse, PREG_PATTERN_ORDER);

        return [
            'balance'  => (isset($balanse[0][0])) ? $balanse[0][0] : 'Not found',
            'credit'   => (isset($balanse[0][1])) ? $balanse[0][1] : 'Not found',
            'currency' => (isset($balanse[0][2])) ? $balanse[0][2] : 'Not found',
        ];


    }


    /**
     * Отправялет запрос к API
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function _request($method, $params)
    {

        try {
            $ch = curl_init();

        } catch (\Exception $e) {
            return [
                'response' => 0,
                'error'    => $e->getMessage(),
            ];
        }

        curl_setopt($ch, CURLOPT_URL, self::API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $result = curl_exec($ch);

        if ($result === FALSE)
            return [
                'response' => 0,
                'error'    => curl_error($ch),
            ];


        return $result;

    }


    /**
     * Формирует результат запроса к API
     *
     * @param        $str
     * @param string $method
     *
     * @return array
     */
    public function response($str, $method = '')
    {
        preg_match_all(self::PATTERN_RESPONSE, $str, $smsid, PREG_PATTERN_ORDER);

        if (isset($smsid[1][0])) {
            $arraySmsIds = explode(PHP_EOL, $smsid[1][0]);
            foreach ($arraySmsIds as $k => $id) {
                if (empty(trim($id)))
                    unset($arraySmsIds[$k]);
            }

            if (!empty($arraySmsIds)) {

                if ($method == "status") {
                    foreach ($arraySmsIds as $k => $val) {
                        $detailId = explode(':', $val);
                        $arraySmsIds[$k] = [
                            'id'     => trim($detailId[0]),
                            'status' => trim($detailId[1]),
                        ];
                    }
                }

                return [
                    'response' => 1,
                    'ids'      => $arraySmsIds,
                ];
            } else
                return [
                    'response' => 0,
                    'error'    => 'List ids is empty.',
                ];


        } else {
            return [
                'response' => 0,
                'error'    => $str,
            ];

        }
    }


}

