<?php

namespace NgLamVN\GameHandle;

use _64FF00\PurePerms\PurePerms;
use muqsit\invmenu\InvMenuHandler;
use NgLamVN\GameHandle\CoinSystem\CoinSystem;
use NgLamVN\GameHandle\command\InitCommand;
use NgLamVN\GameHandle\InvCrashFix\IC_PacketHandler;
use NgLamVN\GameHandle\task\InitTask;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Core extends PluginBase
{
    public static $instance;

    public $coin;
    public $achivement;

    public static function getInstance():Core
    {
        return self::$instance;
    }
    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {

        if(!InvMenuHandler::isRegistered())
        {
            InvMenuHandler::register($this);
        }

        $plmanager = $this->getServer()->getPluginManager();
        $plmanager->registerEvents(new EventListener($this), $this);
        $plmanager->registerEvents(new IC_PacketHandler(), $this);
        $cmd = new InitCommand($this);
        $task = new InitTask($this);
        $this->coin = new CoinSystem($this);
    }
    public function onDisable()
    {
        $this->getAchivementManager()->saveData();
    }

    public function CreateIsland (Player $player)
    {
        Server::getInstance()->dispatchCommand($player, "is auto");
        Server::getInstance()->dispatchCommand($player, "is claim");
        $player->sendMessage("Lest Play !");
    }

    public function getPP(): PurePerms
    {
        return $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    }

    public function getPlayerGroupName(Player $player)
    {
        $group = $this->getPP()->getUserDataMgr()->getGroup($player)->getName();
        return $group;
    }

    public function getCoinSystem():CoinSystem
    {
        return $this->coin;
    }
    public function getAchivementManager(): AchivementManager
    {
        return $this->achivement;
    }
}
