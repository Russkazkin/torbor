<?php

namespace app\modules;

use \yii\base\BaseObject;

/**
 * Class PriceDiffControl
 *
 * @package app\modules
 *
 * @param int $allowedVariation Допустимое отклонение в процентах
 * @param int $price Текущая цена
 * @param int|null $oldPrice Предыдущая цена
 * @param bool $_result Результат проверки цен
 *
 * Класс контролирует наценку/скидку между текущей ценой и предыдущей
 * Примеры:
 * $control = new PriceDiffControl(15, 120, 100);
 * var_dump($model->result); //int(0) Так как разница цен 20 процентов, что больше нормы в 15
 *
 * $control = new PriceDiffControl(15, 105, 100);
 * var_dump($model->result); //int(1) Так как разница цен 5 процентов, что укладывается в норму
 *
 * $control = new PriceDiffControl(15, 105);
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
        $this->allowedVariation = $allowedVariation;
        $this->price = $price;
        $this->oldPrice = $oldPrice ? $oldPrice : $price;
        parent::__construct($config);

    }

    /**
     * Метод инициализирует private свойство $_result и сохраняет в него результат проверки цен. Свойство доступно через геттер getResult()
     */
    public function init()
    {
        parent::init();
        $this->_result = $this->diff($this->price, $this->oldPrice, $this->allowedVariation);
    }

    /**
     * Метод находит процентное изменение
     * и сранивает с допустимым значением.
     * Если изменение не укладывается в норму, возращает false.
     * В противном случае true
     * @param $price
     * @param $oldPrice
     * @param $allowedVariation
     * @return bool
     */
    public function diff(int $price, int $oldPrice, int $allowedVariation) : bool
    {
        $diff = abs(($price - $oldPrice) / $oldPrice) * 100;
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