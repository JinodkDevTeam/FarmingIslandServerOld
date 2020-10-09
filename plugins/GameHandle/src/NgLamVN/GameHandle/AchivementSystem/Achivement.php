<?php

namespace NgLamVN\GameHandle\AchivementSystem;

use pocketmine\item\Item;
use NgLamVN\GameHandle\AchivementSystem\AchivementManager;

class Achivement
{
    public $name, $id, $type, $level, $item, $des;

    /**
     * Achivement constructor.
     * @param string $name
     * @param int $id
     * @param string $type
     * @param array $level
     * @param int|string $item
     * @param string $des
     */

    public function __construct(string $name, int $id, string $type, array $level, $item, string $des = "")
    {
        $this->name = $name;
        $this->id = $id;
        $this->type = $type;
        $this->level = $level;
        $this->item = $item;
        $this->des = $des;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        if ($this->item == "all")
            return Item::get(Item::AIR);
        return Item::get($this->id);
    }

    /**
     * @return array
     */
    public function getAllLevel(): array
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return array
     */
    public function getLevel(int $level): array
    {
        return $this->level[$level];
    }

    /**
     * @param int $level
     * @return int
     */
    public function getCount(int $level): int
    {
        return $this->level[$level]["count"];
    }

    /**
     * @param int $level
     * @return int
     */
    public function getReward(int $level): int
    {
        return $this->level[$level]["reward"];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->des;
    }
}
