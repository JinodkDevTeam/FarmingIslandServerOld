<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle;

use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

/**
 * Class PlayerStat
 * @package NgLamVN\GameHandle
 */
class PlayerStat
{
    /** @var Player  */
    protected Player $player;
    /** @var bool  */
    private bool $isFly = false;
    /** @var bool  */
    private bool $isMuted = false;
    /** @var int  */
    private int $mute_time = 0;
    /** @var int  */
    private int $mute_start_time = 0;
    /** @var bool  */
    private bool $isFreeze = false;
    /** @var int  */
    private int $freeze_time = 0;
    /** @var int  */
    private int $freeze_start_time = 0;
    /** @var Position|null  */
    private ?Position $death_pos = null;

    /**
     * PlayerStat constructor.
     * @param Player $player
     */

    public function toArray()
    {
        return [
            $this->getPlayer(),
            $this->isFly(),
            $this->isMuted(),
            $this->getMuteTime(),
            $this->getMuteStartTime(),
            $this->isFreeze(),
            $this->getFreezeTime(),
            $this->getFreezeStartTime(),
            $this->getDeathPos()
        ];
    }

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function getCore(): ?Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
    }

    /**
     * @return bool
     */
    public function isFly(): bool
    {
        return $this->isFly;
    }

    /**
     * @return bool
     */
    public function isMuted(): bool
    {
        if ($this->isMuted)
        {
            if (time() > $this->getExpireTime($this->getMuteTime(), $this->getMuteStartTime()))
            {
                $this->setMute(false);
            }
        }
        return $this->isMuted;
    }

    /**
     * @return bool
     */
    public function isFreeze(): bool
    {
        if (time() > $this->getExpireTime($this->getFreezeTime(), $this->getFreezeStartTime()))
        {
            $this->setFreeze(false);
        }
        return $this->isFreeze;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getMuteTime(): int
    {
        return $this->mute_time;
    }

    /**
     * @return int
     */
    public function getMuteStartTime(): int
    {
        return $this->mute_start_time;
    }

    /**
     * @return int
     */
    public function getFreezeTime(): int
    {
        return $this->freeze_time;
    }

    /**
     * @return int
     */
    public function getFreezeStartTime(): int
    {
        return $this->freeze_start_time;
    }

    /**
     * @return Position|null
     */
    public function getDeathPos(): ?Position
    {
        return $this->death_pos;
    }

    /**
     * @param $time
     * @param $starttime
     * @return int
     */
    protected function getExpireTime($time, $starttime): int
    {
        if (($time + $starttime) > PHP_INT_MAX) return PHP_INT_MAX;
        return $time + $starttime;
    }

    /**
     * @param bool $status
     */
    public function setFly(bool $status = true)
    {
        $this->isFly = $status;
        $this->save();
    }

    /**
     * @param bool $status
     * @param int $time
     */
    public function setMute(bool $status = true, int $time = PHP_INT_MAX)
    {
        $this->isMuted = $status;
        if ($status)
        {
            $this->setMuteTime($time);
            $this->setMuteStartTime(time());
        }
        else
        {
            $this->setMuteTime(0);
            $this->setMuteStartTime(0);
        }
        $this->save();
    }

    /**
     * @param int $time
     */
    public function setMuteTime(int $time = 0)
    {
        $this->mute_time = $time;
        $this->save();
    }

    /**
     * @param int $time
     */
    public function setMuteStartTime(int $time = 0)
    {
        $this->mute_start_time = $time;
        $this->save();
    }

    /**
     * @param bool $status
     * @param int $time
     */
    public function setFreeze(bool $status = true, int $time = PHP_INT_MAX)
    {
        $this->isFreeze = $status;
        if ($status)
        {
            $this->setFreezeTime($time);
            $this->setFreezeStartTime(time());
        }
        else
        {
            $this->setFreezeTime(0);
            $this->setFreezeStartTime(0);
        }
        $this->save();
    }

    /**
     * @param int $time
     */
    public function setFreezeTime(int $time = 0)
    {
        $this->freeze_time = $time;
        $this->save();
    }

    /**
     * @param int $time
     */
    public function setFreezeStartTime(int $time = 0)
    {
        $this->freeze_start_time = $time;
        $this->save();
    }

    /**
     * @param Position|null $pos
     */
    public function setDeathPos(?Position $pos)
    {
        $this->death_pos = $pos;
        $this->save();
    }

    public function save()
    {
        $this->getCore()->getPlayerStatManager()->stats[$this->getPlayer()->getName()] = $this;
    }
}
