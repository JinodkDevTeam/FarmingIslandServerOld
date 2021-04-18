<?php

namespace malgn\CustomBreakTimeAPI;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

abstract class BaseBreakTime
{
    /** @var string $name */
    protected $name;

    /**
     * BaseBreakTime constructor.
     * @param Item $item
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Block $block
     * @param Item $itemuse
     * @param Player $player
     * @return int
     */
    public function getBreakTime(Block $block, Item $itemuse, Player $player)
    {
        return 0;
    }
    /**
     * @return string
     * Return name of Base break time config
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Vector3 $pos
     * @param Player $player
     * @param Item $item
     */
    public function onBreak(Vector3 $pos, Player $player, Item $item)
    {
        $player->getLevelNonNull()->useBreakOn($pos, $item, $player, true);
    }
}