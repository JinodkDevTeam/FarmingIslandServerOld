<?php

declare(strict_types=1);

/**
 * Addition API for compatible with plugin using PointAPI by phuongaz
 */

use onebone\pointapi\PointAPI;
use pocketmine\Player;

class EconomyAPI
{
    public static $instance;

    public static function getInstance(): EconomyAPI
    {
        self::$instance = $this;
        return self::$instance;
    }

    public function setMoney(Player $player, int $amount)
    {
        PointAPI::getInstance()->setPoint($player, $amount);
    }

    public function addMoney(Player $player, int $amount)
    {
        PointAPI::getInstance()->addPoint($player, $amount);
    }
    public function reduceMoney(Player $player, int $amount)
    {
        PointAPI::getInstance()->reducePoint($player, $amount);
    }
    public function myMoney(Player $player)
    {
        PointAPI::getInstance()->myPoint($player);
    }
}
