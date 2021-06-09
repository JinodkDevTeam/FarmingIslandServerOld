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

use czechpmdevs\multiworld\form\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ManageSubcommand implements SubCommand {

    public function executeSub(CommandSender $sender, array $args, string $name): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can be used only in-game!");
            return;
        }

        $form = new SimpleForm("World Manager", "Select action");
        $form->mwId = 0;
        $form->addButton("Create world");
        $form->addButton("Delete world");
        $form->addButton("Manage world GameRules");
        $form->addButton("Get information about worlds");
        $form->addButton("Load or unload world");
        $form->addButton("Teleport to level");
        $form->addButton("Teleport player to level");
        $form->addButton("Update lobby/spawn position");

        $sender->sendForm($form);
    }

}