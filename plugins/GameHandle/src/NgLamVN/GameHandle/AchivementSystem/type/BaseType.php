<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use NgLamVN\GameHandle\AchivementSystem\Achivement;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use NgLamVN\GameHandle\AchivementSystem\AchivementManager;

abstract class BaseType implements Listener
{
    public $a;

    /**
     * BaseType constructor.
     * @param Achivement $a
     */
    public function __construct(Achivement $a)
    {
        $this->a = $a;
    }

    /**
     * @return Achivement
     */
    public function getAchivement(): Achivement
    {
        return $this->a;
    }

    /**
     * @return AchivementManager
     */
    public function getAManager(): AchivementManager
    {
        return AchivementManager::getInstance();
    }

}