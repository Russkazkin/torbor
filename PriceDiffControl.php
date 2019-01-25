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
 * @param int $_amount Разница между текущей ценой и предыдущей в процентах
 *
 * Класс контролирует наценку/скидку между текущей ценой и предыдущей
 * Примеры:
 * $control = new PriceDiffControl(15, 120, 100);
 * var_dump($control->amount); //int(20) Процентная разница 20 процентов
 * var_dump($control->diff()); //bool(false) Превышает норму в 15 процентов
 *
 * $control = new PriceDiffControl(15, 114, 100);
 * var_dump($control->amount); //int(14) Процентная разница 14 процентов
 * var_dump($control->diff()); //bool(true) Укладывается в норму в 15 процентов
 *
 * $control = new PriceDiffControl(15, 105);
 * var_dump($control->amount); //int(0) Предыдущая цена отсутствует, процентная разница - 0 процентов
 * var_dump($control->diff()); //bool(true) Укладывается в норму в 15 процентов
 */

class PriceDiffControl extends BaseObject
{
    public $allowedVariation;
    public $price;
    public $oldPrice;
    private $_amount;

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
     * Метод инициализирует private свойство $_amount,
     * рассчитывает процентную разницу между текущей ценой и предыдущей
     * и сохраняет разницу в процентах в свойство $_amount
     */
    public function init()
    {
        parent::init();
        $this->_amount = abs(($this->price - $this->oldPrice) / $this->oldPrice) * 100;
    }

    /**
     * Метод сравнивает процентное изменение
     * с допустимым значением.
     * Если изменение не укладывается в норму, возращает false.
     * В противном случае true
     *
     * @return bool
     */
    public function diff() : bool
    {
        return $this->_amount > $this->allowedVariation ? false : true;
    }

    /**
     * Геттер для получения свойства $_amount
     * @return int
     */
    public function getAmount() : int
    {
        return $this->_amount;
    }
}