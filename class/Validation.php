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
        /** @var  $paramsClient - делаем ассоциативный массив  чтоб обращаться было проще*/
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