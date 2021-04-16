<?php

namespace malgn\CustomBreakTimeAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\types\GameMode;

class EventHandler implements Listener
{
    /** @var CustomBreakTimeAPI $api */
    public $api;
    /** @var BreakTask[] */
    protected $task = [];

    public function __construct(CustomBreakTimeAPI $api)
    {
        $this->api = $api;
    }

    public function getAPI(): CustomBreakTimeAPI
    {
        return $this->api;
    }

    public function onRecieve (DataPacketReceiveEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::SURVIVAL)
        {
            return;
        }
        $packet = $event->getPacket();
        if ($packet instanceof PlayerActionPacket)
        {
            switch ($packet->action)
            {
                case PlayerActionPacket::ACTION_START_BREAK:
                    $item = $player->getInventory()->getItemInHand();
                    $basetime = CustomBreakTimeAPI::getBaseBreakTime($item);
                    if ($basetime == null) return;
                    $pos = new Vector3($packet->x, $packet->y, $packet->z);
                    $block = $player->getLevelNonNull()->getBlock($pos);
                    $time = $basetime->getBreakTime($block, $item, $player);
                    $this->getAPI()->setBreakStatus($player, true);
                    $this->task[$player->getName()] = new BreakTask($player, $pos, $this->getAPI());
                    $this->getAPI()->getScheduler()->scheduleDelayedTask($this->task[$player->getName()], $time);
                    break;
                case PlayerActionPacket::ACTION_ABORT_BREAK:
                case PlayerActionPacket::ACTION_STOP_BREAK:
                    $this->getAPI()->setBreakStatus($player, false);
                    if (isset($this->task[$player->getName()]))
                    $this->task[$player->getName()]->cancel();
                    break;
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->isCancelled())
        {
            $this->getAPI()->setBreakStatus($event->getPlayer(), false);
        }
    }
}