<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\CoinSystem;

use NgLamVN\GameHandle\Core;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class CoinSystem
{
    public static CoinSystem $instance;

    public $cfg, $data;

    const DEFAULT_COIN = 0;

    public function __construct(Core $plugin)
    {
        $this->getData();
        self::$instance = $this;
        $plugin->getServer()->getCommandMap()->register("coin", new CoinCommand($plugin, $this));
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function getCore(): ?Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
    }

    public function getCoinDataFolder()
    {
        $folder = $this->getCore()->getDataFolder() . "Coin/";
        if (!file_exists($folder))
        {
            @mkdir($folder);
        }
        return $folder;
    }
    public function saveData()
    {
        $this->cfg->setAll($this->data);
        $this->cfg->save();
    }

    public function getData()
    {
        $this->cfg = new Config($this->getCoinDataFolder() . "coin.yml", Config::YAML);
        $this->data = $this->cfg->getAll();
    }

    /**
     * @param Player|string $player
     * @return float
     */
    public function getCoin($player)
    {
        if ($player instanceof Player)
        {
            $player = $player->getName();
        }
        if (!isset($this->data[$player]))
        {
            $this->data[$player] = self::DEFAULT_COIN;
            $this->saveData();
        }
        return $this->data[$player];
    }

    /**
     * @param Player|string $player
     * @return bool
     */
    public function IsHasData ($player): bool
    {
        if ($player instanceof Player)
        {
            $player = $player->getName();
        }
        if (isset($this->data[$player]))
        {
            return true;
        }
        return false;
    }

    /**
     * @param Player|string $player
     * @param float $amount
     * @return float
     * @todo HMMMMM
     */
    public function setCoin($player, $amount)
    {
        if ($player instanceof Player)
        {
            $player = $player->getName();
        }
        $this->data[$player] = $amount;
        $this->saveData();
    }

    /**
     * @param Player|string $player
     * @param float $amount
     */
    public function reduceCoin ($player, $amount)
    {
        $this->setCoin($player, $this->getCoin($player) - $amount);
        $this->saveData();
    }

    /**
     * @param Player|string $player
     * @param float $amount
     */
    public function addCoin ($player, $amount)
    {
        $this->setCoin($player, $this->getCoin($player) + $amount);
        $this->saveData();
    }
}
