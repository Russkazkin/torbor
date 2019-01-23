<?php

namespace app\models;

use \yii\base\BaseObject;

/**
 * Class PriceDiffControl
 * @package app\models
 * Примеры:
 * $model = new PriceDiffControl(15, 120, 100);
 * var_dump($model->result); //int(0) Так как разница цен 20 процентов, что больше нормы в 15
 *
 * $model = new PriceDiffControl(15, 105, 100);
 * var_dump($model->result); //int(1) Так как разница цен 5 процентов, что укладывается в норму
 *
 * $model = new PriceDiffControl(15, 105);
 * var_dump($model->result); //int(1) Старая цена отсутствует, мы сравниваем текущую цену с текущей ценой, что дает нам true на выходе
 */

class PriceDiffControl extends BaseObject
{
    public $allowedVariation;
    public $price;
    public $oldPrice;
    private $_result;

    /**
     * PriceDiffControl constructor.
     * @param int $allowedVariation Допустимое отклонение в процентах
     * @param int $price Текущая цена
     * @param int|null $oldPrice Предыдущая цена
     * @param array $config
     */
    public function __construct(int $allowedVariation, int $price, int $oldPrice = null, array $config = [])
    {
        parent::__construct($config);
        $this->allowedVariation = $allowedVariation;
        $this->price = $price;
        $this->oldPrice = $oldPrice ? $oldPrice : $price;
        $this->_result = $this->diff($this->price, $this->oldPrice, $this->allowedVariation);
    }

    /**
     * Метод сравнивает два числа,
     * делит большее на меньшее,
     * преобразует полученный коэффициент в процент разницы
     * и сранивает с допустимым значением этой разницы.
     * Если разница не укладывается в норму, возращает false.
     * В противном случае true
     * @param $num1
     * @param $num2
     * @param $allowedVariation
     * @return bool
     */
    public function diff($num1, $num2, $allowedVariation) : bool
    {
        $rate = $num1 >= $num2 ? $num1 / $num2 : $num2 / $num1;
        $diff = ($rate - 1) * 100;
        return $diff > $allowedVariation ? false : true;
    }

    /**
     * Получем разультат сравнения цен
     * @return int
     */
    public function getResult() : int
    {
        return $this->_result;
    }
}