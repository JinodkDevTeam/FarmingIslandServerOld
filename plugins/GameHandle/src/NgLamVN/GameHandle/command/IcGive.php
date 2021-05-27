<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

class IcGive extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("icgive", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Give a item from Item code (using InvCraft save format)");
        $this->setPermission("gh.icgive");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player)
        {
            $sender->sendMessage("Use in-game only");
            return;
        }
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.icgive"))
            {
                $sender->sendMessage("You not have permission to use this command !");
                return;
            }
            $stream = new BigEndianNBTStream();
            $code = hex2bin($args[0]);
            if ($code == false)
            {
                $sender->sendMessage("Failed to decode item code !");
                return;
            }
            $nbt = $stream->readCompressed($code);
            if (!$nbt instanceof CompoundTag)
            {
                $sender->sendMessage("Failed to decode item code to item NBT !");
                return;
            }
            $item = Item::nbtDeserialize($nbt);
            if (!$sender->getInventory()->canAddItem($item))
            {
                $sender->sendMessage("Failed to add item to your inventory, make sure you have enough space !");
                return;
            }
            $sender->getInventory()->addItem($item);
            $sender->sendMessage("Item Added !");
        }
        else
        {
            $sender->sendMessage("/icgive <item_code>");
        }
    }
}
