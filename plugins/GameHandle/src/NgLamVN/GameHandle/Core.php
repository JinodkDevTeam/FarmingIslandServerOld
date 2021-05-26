<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle;

use _64FF00\PurePerms\PurePerms;
use muqsit\invmenu\InvMenuHandler;
use NgLamVN\GameHandle\ChatThin\CT_PacketHandler;
use NgLamVN\GameHandle\CoinSystem\CoinSystem;
use NgLamVN\GameHandle\command\InitCommand;
use NgLamVN\GameHandle\InvCrashFix\IC_PacketHandler;
use NgLamVN\GameHandle\task\InitTask;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Record;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Core extends PluginBase
{
    /** @var int[] */
    public array $afktime = [];

    public static Core $instance;

    public CoinSystem $coin;

    public PlayerStatManager $pstatmanager;
    /** @var Skin[] */
    public array $skin = [];

    public static function getInstance(): Core
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
        $plmanager->registerEvents(new CT_PacketHandler(), $this);
        $cmd = new InitCommand($this);
        $task = new InitTask($this);
        $this->coin = new CoinSystem($this);
        $this->pstatmanager = new PlayerStatManager();

        ItemFactory::registerItem(new Record(759, LevelSoundEventPacket::SOUND_RECORD_PIGSTEP), true); //PIGSTEP
        Item::addCreativeItem(new Record(759, LevelSoundEventPacket::SOUND_RECORD_PIGSTEP));
    }
    public function onDisable()
    {
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

    public function getCoinSystem(): CoinSystem
    {
        return $this->coin;
    }

    public function getPlayerStatManager(): PlayerStatManager
    {
        return $this->pstatmanager;
    }
}
