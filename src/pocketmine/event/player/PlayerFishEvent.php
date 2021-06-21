<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\FishingHook;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;

class PlayerFishEvent extends PlayerEvent implements Cancellable{
	
	public const STATE_FISHING = 0;
	public const STATE_CAUGHT_FISH = 1;
	public const STATE_CAUGHT_ENTITY = 2;
	public const STATE_CAUGHT_NOTHING = 3;

	/** @var FishingHook */
	protected $hook;
	/** @var int */
	protected $xpDropAmount = 0;
	/** @var int */
	protected $state = 0;
    /** @var mixed */
	protected $itemResult = null;

	public function __construct(Player $fisher, FishingHook $hook, int $state, int $xpDropAmount = 0, $itemResult = []){
		$this->player = $fisher;
		$this->hook = $hook;
		$this->state = $state;
		$this->xpDropAmount = $xpDropAmount;
		$this->itemResult = $itemResult;
	}

	public function getCaughtEntity() : ?Entity{
		return $this->hook->getRidingEntity();
	}

	/**
	 * @return FishingHook
	 */
	public function getHook() : FishingHook{
		return $this->hook;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int{
		return $this->xpDropAmount;
	}

	/**
	 * @param int $xpDropAmount
	 */
	public function setXpDropAmount(int $xpDropAmount) : void{
		$this->xpDropAmount = $xpDropAmount;
	}

	/**
	 * @return int
	 */
	public function getState() : int{
		return $this->state;
	}

    /**
     * @return Item[]|null
     */
	public function getItemResult()
    {
        $items = $this->itemResult;
        $result = [];
        if (isset($items))
        {
            foreach ($items as $item)
            {
                array_push($result, Item::get($item->getId(), $item->getDamage(), $item->getCount(), $item->getNamedTag()));
            }
            return $result;
        }
        return null;
    }

    /**
     * @param Item[] $item
     */
    public function setItemResult(array $items)
    {
        $itemr = [];
        foreach ($items as $item)
        {
            array_push($itemr, ItemFactory::get($item->getId(), $item->getDamage(), $item->getCount(), $item->getNamedTag()));
        }
        $this->itemResult = $itemr;
    }
}