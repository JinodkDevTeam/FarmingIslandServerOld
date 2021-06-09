<?php

/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2021  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace czechpmdevs\multiworld\command\subcommand;

use czechpmdevs\multiworld\MultiWorld;
use czechpmdevs\multiworld\util\LanguageManager;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class LoadSubcommand implements SubCommand {

    public function executeSub(CommandSender $sender, array $args, string $name): void {
        if (!isset($args[0])) {
            $sender->sendMessage(LanguageManager::getMsg($sender, "load-usage"));
            return;
        }

        if (!$this->getServer()->isLevelGenerated($args[0])) {
            $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::getMsg($sender, "load-levelnotexists", [$args[0]]));
            return;
        }

        if ($this->getServer()->isLevelLoaded($args[0])) {
            $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::getMsg($sender, "load-loaded"));
            return;
        }

        $this->getServer()->loadLevel($args[0]);
        $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::getMsg($sender, "load-done"));
    }

    private function getServer(): Server {
        return Server::getInstance();
    }
}
