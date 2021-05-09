<?php

namespace NgLamVN\GameHandle;

use NgLamVN\GameHandle\task\AutoJoinIslandTask;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use NgLamVN\GameHandle\GameMenu\Menu;

use MyPlot\MyPlot;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class EventListener implements Listener
{
    public $plugin, $menu, $fish;

    public function __construct(Core $plugin)
    {
        $this->plugin = $plugin;
        $this->menu = new Menu();
        $this->fish = new FishingManager();
    }

    public function getCore(): Core
    {
        return $this->plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     * @priority HIGHEST
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getLevel()->getName() !== "island")
        {
            Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp island ". $player->getName());
        }

        $this->menu->registerMenuItem($player);
        $this->menu->sendUpdatesForm($player);
        Server::getInstance()->dispatchCommand($player, "is home");
    }

    /**
     * @param PlayerRespawnEvent $event
     * @priority HIGHEST
     */
    public function onRespawn (PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();

        $plot = MyPlot::getInstance()->getPlotsOfPlayer($player->getName(), "island")[0];

        $plotLevel = MyPlot::getInstance()->getLevelSettings($plot->levelName);
        $pos = MyPlot::getInstance()->getPlotPosition($plot);
        $pos->x += floor($plotLevel->plotSize / 2) + 0.5;
        $pos->y += 1;
        $pos->z -= -90.5;

        $event->setRespawnPosition($pos);
    }

    public function onTap(PlayerInteractEvent $event)
    {
        $this->menu->onTap($event);
    }

    /**
     * @param PlayerFishEvent $event
     * @priority LOWEST
     */
    public function onFish(PlayerFishEvent $event)
    {
        $this->fish->onFish($event);
    }

    public function onDrop (PlayerDropItemEvent $event)
    {
        $this->menu->onDrop($event);
    }

    public function onTrans (InventoryTransactionEvent $event)
    {
        $this->menu->onTrans($event);
    }

    public function onCommand (PlayerCommandPreprocessEvent $event) //TODO: CmdSnooper
    {
        $player = $event->getPlayer();
        $msg = $event->getMessage();
        if ($msg[0] == "/") {
            $this->getCore()->getServer()->getLogger()->info("[CMD][" . $player->getName() . "] use " . $msg);
        }
    }

    public function onChangeLevel (EntityLevelChangeEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player)
        {
            $this->getCore()->afktime[$entity->getName()] = 0;
        }
    }

    public function onQuit (PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $this->getCore()->afktime[$player->getName()] = 0;
    }
}