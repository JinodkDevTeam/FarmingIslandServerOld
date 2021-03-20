<?php

namespace vixikhd\snow;

use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\Task;

class SnowTask extends Task
{
    /** @var Snow  */
    public $plugin;

    /** @var bool  */
    public $rain = true;

    /**
     * SnowTask constructor.
     * @param Snow $plugin
     */
    public function __construct(Snow $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @return bool
     */
    public function isRain(): bool
    {
        return $this->rain;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        $players = $this->plugin->getServer()->getOnlinePlayers();
        if (mt_rand(0,1) == 1) {$this->rain = true;} else {$this->rain = false;}
        if ($this->isRain() == true)
            foreach ($players as $player)
            {
                $pk = new LevelEventPacket();
                $pk->evid = LevelEventPacket::EVENT_START_RAIN;
                $pk->data = 2000;

                $player->dataPacket($pk);
            }
        else
            foreach ($players as $player)
            {
                $pk = new LevelEventPacket();
                $pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
                $pk->data = 1;

                $player->dataPacket($pk);
            }
    }
}
