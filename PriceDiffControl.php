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
 * $control = new PriceDiffControl(['allowedVariation' => 15, 'price' => 120, 'oldPrice' => 100]);
 * var_dump($control->amount); //int(20) Процентная разница 20 процентов
 * var_dump($control->diff()); //bool(false) Превышает норму в 15 процентов
 *
 * $control = new PriceDiffControl(['allowedVariation' => 15, 'price' => 114, 'oldPrice' => 100]);
 * var_dump($control->amount); //int(14) Процентная разница 14 процентов
 * var_dump($control->diff()); //bool(true) Укладывается в норму в 15 процентов
 *
 * $control = new PriceDiffControl(['allowedVariation' => 15, 'price' => 114]);
 * var_dump($control->amount); //int(0) Предыдущая цена отсутствует, процентная разница - 0 процентов
 * var_dump($control->diff()); //bool(true) Укладывается в норму в 15 процентов
 */

class PriceDiffControl extends BaseObject
{
    public $allowedVariation;
    public $price;
    public $oldPrice = null;
    private $_amount;

    /**
     * Метод проверяет свойство $oldPrice и присваивает ему значение из $price,
     * если его значение не задано или равно 0.
     * Так же инициализирует private свойство $_amount,
     * рассчитывает процентную разницу между текущей ценой и предыдущей
     * и сохраняет разницу в процентах в свойство $_amount
     */
    public function init()
    {
        parent::init();
        $this->oldPrice = $this->oldPrice ? $this->oldPrice : $this->price;
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