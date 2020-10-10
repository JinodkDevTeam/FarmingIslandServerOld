<?php

namespace NgLamVN\GameHandle\AchivementSystem;

use NgLamVN\GameHandle\AchivementSystem\AchivementManager;

class AchivementData
{
    public $playername, $a_data;

    /**
     * AchivementData constructor.
     * @param string $playername
     * @param array $a_data
     */
    public function __construct(string $playername, array $a_data)
    {
        $this->playername = $playername;
        $this->a_data = $a_data;
    }

    /**
     * @return AchivementManager
     */
    public function getManager(): AchivementManager
    {
        return AchivementManager::getInstance();
    }

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playername;
    }

    /**
     * @return array
     */
    public function getAllAchivementData(): array
    {
        return $this->a_data;
    }

    /**
     * @param $id
     * @return array
     */
    public function getAchivementData($id): array
    {
        return $this->a_data[$id];
    }

    /**
     * @param $id
     * @return int
     */
    public function getLevel($id): int
    {
        return $this->a_data[$id]["level"];
    }

    /**
     * @param $id
     * @return int
     */
    public function getCount($id):int
    {
        return $this->a_data[$id]["count"];
    }

    /**
     * @param $id
     * @param $newcount
     */
    public function setCount($id, $newcount): void
    {
        $this->getManager()->playerdata[$this->getPlayerName()][$id]["count"] = $newcount;
    }
    public function setLevel($id, $newlevel): void
    {
        $this->getManager()->playerdata[$this->getPlayerName()][$id]["level"] = $newlevel;
    }

    /**
     * @param $id
     * @return int
     */
    public function getClaimed($id): int
    {
        return $this->a_data[$id]["claimed"];
    }

    /**
     * @param $id
     * @param $level
     * @return bool
     */
    public function isClaimed($id, $level):bool
    {
        if ($this->getCount($id) > $level) {return false;}
        else {return true;}
    }
}
