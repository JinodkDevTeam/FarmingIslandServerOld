<?php

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use NgLamVN\InvCraft\Loader;
use pocketmine\Player;

abstract class BaseMenu
{
    /** @var InvMenu */
    public $menu;
    /** @var Loader */
    public $loader;
    /** @var Player */
    public $player;
    /** @var int */
    public $mode;

    const IIIxIII_MODE = 0;
    const VIxVI_MODE = 1;

    public function __construct(Player $player, Loader $loader, int $mode = null)
    {
        $this->player = $player;
        $this->loader = $loader;
        $this->mode = $mode;
        $this->menu($player);
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Loader
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }

    public function getMode (): int
    {
        return $this->mode;
    }

    /**
     * @param Player $player
     */
    public function menu(Player $player)
    {
    }
}