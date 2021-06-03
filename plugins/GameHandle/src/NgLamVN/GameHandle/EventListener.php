<?php

namespace NgLamVN\GameHandle;

use NgLamVN\GameHandle\task\AutoJoinIslandTask;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use NgLamVN\GameHandle\GameMenu\Menu;

use MyPlot\MyPlot;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class EventListener implements Listener
{
    public Core $plugin;
    public Menu $menu;
    public FishingManager $fish;

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

        $this->getCore()->skin[$player->getName()] = $player->getSkin();

        $this->getCore()->getPlayerStatManager()->registerPlayerStat($player);
    }
    public function onSkinChange (PlayerChangeSkinEvent $event)
    {
        $player = $event->getPlayer();
        $newSkin = $event->getNewSkin();

        $this->getCore()->skin[$player->getName()] = $newSkin;
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

    public function onCommand (PlayerCommandPreprocessEvent $event)
    {
        $player = $event->getPlayer();
        $msg = $event->getMessage();
        if ($msg[0] == "/") {
            $this->getCore()->getServer()->getLogger()->info("[CMD][" . $player->getName() . "] use " . $msg);

            if ($player->hasPermission("gh.notp.bypass")) return;
            $args = explode(" ", $event->getMessage());
            if (!($args[0] == "/tp")) return;
            if (isset($args[3])) return;
            if (!isset($args[1])) return;
            $target = $this->getCore()->getServer()->getPlayer($args[1]);
            if ($target == null) return;
            if ($this->getCore()->getPlayerStatManager()->getPlayerStat($target)->isNoTP())
            {
                $player->sendMessage("§cThis Player Is Not Accepting TP");
                $this->getCore()->getServer()->getLogger()->info("[CMD][" . $player->getName() . "] Command Cancelled due to NoTP");
                $event->setCancelled();
            }
            if (!isset($args[2])) return;
            $target = $this->getCore()->getServer()->getPlayer($args[2]);
            if ($target == null) return;
            if ($this->getCore()->getPlayerStatManager()->getPlayerStat($target)->isNoTP())
            {
                $player->sendMessage("§cThis Player Is Not Accepting TP");
                $this->getCore()->getServer()->getLogger()->info("[CMD][" . $player->getName() . "] Command Cancelled due to NoTP");
                $event->setCancelled();
            }
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
        $this->getCore()->getPlayerStatManager()->removePlayerStat($player);
    }

    /**
     * @param PlayerChatEvent $event
     * @priority LOWEST
     * @ignoreCancelled TRUE
     */
    public function onChat (PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        if ($this->getCore()->getPlayerStatManager()->getPlayerStat($player)->isMuted())
        {
            $event->setCancelled();
        }
    }

    /**
     * @param SignChangeEvent $event
     * @priority HIGHEST
     * @ignoreCancelled TRUE
     */
    /*public function onEditSign (SignChangeEvent $event)
    {
        $player = $event->getPlayer();
        $lines = $event->getLines();
        $pos = $event->getBlock()->asPosition();

        $this->getCore()->getLogger()->info("[SignAdd][".$player->getName()."] edit sign on pos (".$pos->getX()."-".$pos->getY()."-".$pos->getZ().") world:". $pos->getLevel()->getName());
        foreach ($lines as $line)
        {
            $this->getCore()->getLogger()->info("[SignInfo] " . $line);
        }
    }*/

    /**
     * @param PlayerMoveEvent $event
     * @priority LOWEST
     * @ignoreCancelled TRUE
     */
    public function onMove (PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        if ($this->getCore()->getPlayerStatManager()->getPlayerStat($player)->isFreeze())
        {
            $event->setCancelled();
        }
    }
}