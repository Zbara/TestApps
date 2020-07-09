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
    public function checkData(){

        /** @var  $param */
        foreach ($this->params as $param){
            switch ($param['name']) {
                /** проверка имени */
                case "name":
                    $this->checkName($param['value']);
                 break;

                 /** проверка номера */
                case "phone":

                    break;
            }
        }
        return ['result' => count($this->error) === 0, 'error' => $this->error];
    }

    /**
     * @param $name
     * проверка валидности имени
     */
    private function checkName($name) {
        if(!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/', $name)){
            $this->error['name'] = 'Ошибка имени';
        }
    }

    /**
     * @param $phone
     * проверка номера
     */
    private function checkPhone($phone){

    }
}