<?php

namespace onebone\pointapi\task;

use pocketmine\Server;
use pocketmine\Player;

class support extends Server
{
	public function supportcommand ($player, $command): void
	{
		$this->getServer()->dispatchCommand($player, $command);
	}
}