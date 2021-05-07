<?php

/**
 * AutoInv.php – AutoInv
 *
 * Copyright (C) 2015-2018 Jack Noordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack Noordhuis
 *
 */

namespace jacknoordhuis\autoinv;

use jacknoordhuis\autoinv\command\AutoPickUp;
use jacknoordhuis\autoinv\event\EventManager;
use jacknoordhuis\autoinv\util\config\EventConfigurationLoader;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class AutoInv extends PluginBase {

	/** @var EventManager */
	public $eventManager;

	/** @var EventConfigurationLoader */
	private $eventConfigLoader;

	/** @var bool[] */
	public $auto_pickup = [];

	const SETTINGS_CONFIG = "Settings.yml";

	public function onEnable() {
		$this->saveResource(self::SETTINGS_CONFIG);
		$this->setEventManager();
		$this->eventConfigLoader = new EventConfigurationLoader($this, $this->getDataFolder() . self::SETTINGS_CONFIG);

		$this->getServer()->getCommandMap()->register("autopickup", new AutoPickUp($this));
	}

	public function getEventConfigurationLoader() : EventConfigurationLoader {
		return $this->eventConfigLoader;
	}

	public function setEventManager() : bool {
		if(!($this->eventManager instanceof EventManager)) {
			$this->eventManager = new EventManager($this);
			return true;
		}
		return false;
	}

	public function getEventManager() : ?EventManager {
		return $this->eventManager;
	}

	public function setBlockPickupMode(Player $player, bool $mode)
    {
        $this->auto_pickup[$player->getName()] = $mode;
    }

    public function isAutoPickup(Player $player): bool
    {
        if (isset($this->auto_pickup[$player->getName()]))
        {
            return $this->auto_pickup[$player->getName()];
        }
        else return true;
    }

}