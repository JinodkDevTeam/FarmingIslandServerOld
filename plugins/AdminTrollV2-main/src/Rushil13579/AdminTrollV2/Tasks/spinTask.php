<?php

namespace Rushil13579\AdminTrollV2\Tasks;

use pocketmine\Player;

use pocketmine\scheduler\Task;

use pocketmine\math\Vector3;

use Rushil13579\AdminTrollV2\Main;

class spinTask extends Task {
    
    /** @var Main */
    private $main;

    /** @var Player */
    private $victim;

    /** @var Int */
    private $speed;

    private $victimYaw;

    private $angle = 0;

    public function __construct(Main $main, Player $victim, Int $speed){
        $this->main = $main;
        $this->victim = $victim;
        $this->speed = $speed;
        $this->victimYaw = $victim->yaw;
    }

    public function onRun($tick){
        if($this->angle > 360){
            $this->main->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        if(!$this->victim->isOnline()){
            $this->main->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        
        $this->angle += 1.8 * $this->speed;
        $this->victimYaw += 1.8 * $this->speed;
        $this->victim->teleport($this->victim->asVector3(), $this->victimYaw);
    }
}