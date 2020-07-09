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
        /** @var  $param */
        foreach ($this->params as $param) {
            switch ($param['name']) {
                /** проверка имени */
                case "name":
                    $this->checkName($param['value']);
                    break;

                /** проверка номера */
                case "phone":
                    $this->checkPhone($param['value']);
                    break;

                /** проверка email */
                case "email":
                    $this->checkEmail($param['value']);
                    break;

                /** проверка коментария */
                case "comment":
                    $this->checkComment($param['value']);
                    break;
            }
        }
        return ['result' => count($this->error) === 0, 'error' => $this->error];
    }

    /**
     * @param $name
     * проверка валидности имени
     */
    private function checkName($name)
    {
        if (isset($name)) {
            if (strlen($name) >= 2) {
                if (!preg_match("/^[\s\x{600}-\x{6FF}a-zA-Zа-яА-Я]+$/iu", $name))
                    $this->error['name'] = 'Не верный формат';
            } else $this->error['name'] = 'Короткое имя';
        } else $this->error['name'] = 'Введите имя';
    }

    /**
     * @param $phone
     * проверка номера
     */
    private function checkPhone($phone)
    {
        if (!preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $phone)) {
            $this->error['phone'] = 'Ошибка телефона';
        }
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
        if (strlen($text) <= 5 or strlen($text) >= 4094) {
            $this->error['comment'] = 'Очень маленький комментарий!';
        }
    }
}