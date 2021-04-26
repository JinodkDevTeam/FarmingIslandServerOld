<?php

namespace NgLamVN\GameHandle\GameMenu;

use NgLamVN\GameHandle\Core;
use pocketmine\block\Block;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class Menu
{

    public const BAN_BLOCK = [
        Block::CHEST,
        Block::ENCHANTING_TABLE,
        Block::CRAFTING_TABLE,
        Block::FURNACE,
        Block::ANVIL,
        Block::WOODEN_DOOR_BLOCK,
        Block::WOODEN_TRAPDOOR,
        Block::IRON_DOOR_BLOCK,
        Block::IRON_TRAPDOOR
    ];

    public function __construct()
    {
    }

    public function getCore(): ?Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
    }

    public function registerMenuItem(Player $player)
    {
        if ($player->getInventory()->getHotbarSlotItem(8)->getNamedTag()->hasTag("menu-mode"))
        {
            return;
        }
        $i = Item::get(Item::PAPER);
        $nbt = $i->getNamedTag();
        $nbt->setByte("menu", 1);
        $nbt->setString("menu-mode", "gui");
        $i->setNamedTag($nbt);
        $i->setCustomName("Island Menu");
        $i->setLore(["Hold and tap to open menu !"]);
        $player->getInventory()->setItem(8, $i);
    }

    public function onTap(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        $slot = $player->getInventory()->getHeldItemIndex();

        if (in_array($event->getBlock()->getId(), self::BAN_BLOCK))
        {
            return;
        }

        if ($slot == 8)
        {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag("menu-mode")->getValue() == "gui")
            {
                new GuiMenu($player);
            }
            else{
                new UiMenu($player);
            }

        }
    }

    public function onDrop (PlayerDropItemEvent $event)
    {
        $item = $event->getItem();
        $nbt = $item->getNamedTag();
        if ($nbt->getTag("menu") !== null)
        {
            if ($nbt->getTag("menu")->getValue() == 1)
            {
                $event->setCancelled(true);
            }
        }
    }
    public function onTrans (InventoryTransactionEvent $event)
    {
        $trans = $event->getTransaction();
        $actions = $trans->getActions();
        foreach ($actions as $action)
        {
            $nbt = $action->getSourceItem()->getNamedTag();
            if ($nbt->getTag("menu") !== null)
            {
                if ($nbt->getTag("menu")->getValue() == 1)
                {
                    $event->setCancelled(true);
                }
            }
        }
    }
    public function sendUpdatesForm (Player $player)
    {
        return new UpdateInfo($player);
    }
}