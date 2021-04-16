<?php

namespace NgLamVN\GameHandle;

use NgLamVN\GameHandle\task\AutoJoinIslandTask;
use pocketmine\block\Block;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use NgLamVN\GameHandle\GameMenu\Menu;

use MyPlot\MyPlot;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
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
        /*$vector = $event->getBlock()->asVector3();
        $item = $event->getItem();
        $player = $event->getPlayer();
        $event->getPlayer()->getLevel()->useBreakOn($vector, $item, $player, true);*/
    }

    /*public function onPlace (BlockPlaceEvent $event)
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $item = $event->getItem()->pop();
        $player->getInventory()->setItemInHand($item);
        $event->setCancelled();
        $newblock = Block::get(mt_rand(1,255));
        $block->getLevel()->setBlock($block->asVector3(), $newblock, true, true);
    }*/

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
}