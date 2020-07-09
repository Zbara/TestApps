<?php

/**
 * Class Validation
 * Create 09.07.2020 13:50
 */
class Validation
{
    private array $params = [];
    private array $error = [];

    /**
     * Validation constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->params = $data;
    }

    /**
     * @return array
     */
    public function checkData()
    {
        /** @var  $params - поля которые обязательные */
        $postParams = ['name', 'phone', 'email', 'comment'];
        /** @var  $paramsClient - делаем ассоциативный массив  чтоб обращаться было проще */
        $paramsClient = array_column($this->params, 'value', 'name');

        /** @var  $item */
        foreach ($postParams as $item) {
            /** проверям на поля, есть ли они, если поля нет будет ошибка */
            if (isset($paramsClient[$item])) {
                switch ($item) {
                    /** проверка имени */
                    case "name":
                        $this->checkName($paramsClient[$item]);
                        break;

                    /** проверка номера */
                    case "phone":
                        $this->checkPhone($paramsClient[$item]);
                        break;

                    /** проверка email */
                    case "email":
                        $this->checkEmail($paramsClient[$item]);
                        break;

                    /** проверка коментария */
                    case "comment":
                        $this->checkComment($paramsClient[$item]);
                        break;
                }
            } else $this->error[$item] = "Параметр - {$item} пуст.";
        }
        return ['result' => count($this->error) === 0, 'error' => $this->error];
    }

    /**
     * @param $name
     * проверка валидности имени
     */
    private function checkName($name)
    {
        /** @var  $name - проверка на спец символы */
        $filter = $this->filter($name);

        if ($filter) {
            $this->error['name'] = 'В строке есть запрещеные символы, такие ' . $filter;
        } elseif (strlen($name) < 3) {
            $this->error['name'] = 'Имя не может быть меньше 3 символов';
        } elseif (strlen($name) > 32) $this->error['name'] = 'Имя не может быть больше 32 символов';
    }

    /**
     * @param $text
     * @return string
     * проверка на запрещеные символы
     */
    private function filter($text)
    {
        /** @var  $error */
        $error = [];

        /** @var  $strip */
        $strip = ["1", "2", "3", "4", "5", "6", "7", "8", "9",
            "0", "'", ",", "/", ";", ":", "@", "[", "]", "{",
            "}", "=", ")", "(", "*", "&", "^", "%", "$", "<",
            ">", "?", "!", '"'
        ];
        /** @var  $text */
        $text = str_split($text);

        /** @var  $item */
        foreach ($text as $item) {
            if (in_array($item, $strip)) {
                $error[] = $item;
            }
        }
        return implode('', $error);
    }

    /**
     * @param $phone
     * @return bool
     */
    private function checkPhone($phone)
    {
        /** @var  $сodePhone - массив кодов стран */
        $country = ['380', '7', '1', '61', '213', '1246', '375'];
        /** @var  $number */
        $number = [];
        /** @var  $item  - разбиваем на массив, и чистим от лишних лимволов */
        foreach (str_split($phone) as $item) {
            if (!in_array($item, ['(', ')', ' ', '-', '+'])) {
                $number[] = $item;
            }
        }
        /** @var  $value - проверка валидности номера*/
        foreach ($country as $value) {
            /** @var  $length - узнаем длину кода номера , который ножно проверить */
            $length = (int)count(str_split($value));

            /** сравниваем коды номеров */
            if (join('', array_slice($number, 0, $length)) == $value) {
                /** если длина верная тогда выпускаем без ошибки */
                if (count($number) >= 10 or count($number) <= 14) {
                    return true;
                }
            }
        }
        $this->error['phone'] = 'Ошибка телефона';
    }

    /**
     * @param $email
     * проверка имейла
     */
    private function checkEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Не верный email.';
        }
    }

    /**
     * @param $text
     * проверка комментария
     */
    private function checkComment($text)
    {
        /** @var  $text - очистика от мусора */
        $text = strip_tags($text);

        if (strlen($text) <= 5 or strlen($text) >= 4094) {
            $this->error['comment'] = 'Очень маленький комментарий!';
        }
    }
}