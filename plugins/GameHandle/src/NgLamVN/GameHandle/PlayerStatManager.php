<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle;

use pocketmine\Player;

class PlayerStatManager
{
    /** @var PlayerStat[] */
    public array $stats;

    /**
     * PlayerStatManager constructor.
     */
    public function __construct()
    {
        //TODO: Construct
    }

    /**
     * @param Player $player
     * @return PlayerStat
     */
    public function getPlayerStat(Player $player): PlayerStat
    {
        if (!isset($this->stats[$player->getName()]))
        {
            $this->registerPlayerStat($player);
        }
        return $this->stats[$player->getName()];
    }

    /**
     * @return PlayerStat[]
     */
    public function getAllPlayerStat(): array
    {
        if (isset($this->stats)) return $this->stats;
        else return [];
    }

    /**
     * @param Player $player
     */
    public function removePlayerStat(Player $player)
    {
        if (isset($this->stats[$player->getName()]))
        {
            unset($this->stats[$player->getName()]);
        }
    }

    public function registerPlayerStat(Player $player, $overwrite = false)
    {
        if (isset($this->stats[$player->getName()]))
        {
            if (!$overwrite)
            {
                throw new \Exception("Can't overwrite available PlayerStat");
            }
        }
        $this->stats[$player->getName()] = new PlayerStat($player);
    }
}