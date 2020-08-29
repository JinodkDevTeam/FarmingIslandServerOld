<?php

namespace NgLamVN\GameHandle;

use NgLamVN\GameHandle\task\AutoJoinIslandTask;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;

use NgLamVN\GameHandle\Core;
use NgLamVN\GameHandle\GameMenu\Menu;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;

use MyPlot\MyPlot;
use pocketmine\event\player\PlayerRespawnEvent;
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


    /**
     * @param PlayerJoinEvent $event
     * @priority LOWEST
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
     * @priority LOWEST
     */
    public function onRespawn (PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getLevel()->getName() == "island")
        {
            $plot = MyPlot::getInstance()->getPlotsOfPlayer($player->getName(), "island")[0];

            $plotLevel = MyPlot::getInstance()->getLevelSettings($plot->levelName);
            $pos = MyPlot::getInstance()->getPlotPosition($plot);
            $pos->x += floor($plotLevel->plotSize / 2) + 0.5;
            $pos->y += 1;
            $pos->z -= -90.5;

            $event->setRespawnPosition($pos);
        }
    }

    public function onTap(PlayerInteractEvent $event)
    {
        $this->menu->onTap($event);
    }

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
}