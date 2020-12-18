<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\GameHandle\Core;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;

class UiMenu
{
    public function __construct(Player $player)
    {
        $this->MenuForm($player);
    }

    public function getCore(): ?Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("GameHandle");
    }

    public function MenuForm(Player $player)
    {
        $list = [];
        if ($player->getLevel()->getName() == "island")
        {
            array_push($list, "is-manager");
            array_push($list, "is-info");
        }
        array_push($list, "teleport");
        array_push($list, "achievement");
        array_push($list, "shop");
        array_push($list, "vip-shop");
        array_push($list, "sell-all");
        array_push($list, "coin");
        array_push($list, "vip");
        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber"]))
        {
            array_push($list, "rankcolor");
        }
        array_push($list, "gui");

        $form = new SimpleForm(function (Player $player, $data) use ($list)
        {
            if (!isset($data))
            {
                return;
            }
            switch ($list[$data])
            {
                case "coin":
                    Server::getInstance()->dispatchCommand($player, "coin");
                    break;
                case "is-manager":
                    return new IslandManager($player);
                    break;
                case "teleport":
                    return new TeleportManager($player);
                    break;
                case "rankcolor":
                    Server::getInstance()->dispatchCommand($player, "rankcolor");
                    break;
                case "vip":
                    return new VipManager($player);
                    break;
                case "shop":
                    Server::getInstance()->dispatchCommand($player, "shop");
                    break;
                case "sell-all":
                    Server::getInstance()->dispatchCommand($player, "sell all");
                    break;
                case "vip-shop":
                    Server::getInstance()->dispatchCommand($player, "cuahang");
                    break;
                case "achievement":
                    Server::getInstance()->dispatchCommand($player, "achievement");
                    break;
                case "gui":
                    $this->GuiMode($player);
                    break;
            }
        });
        if ($player->getLevel()->getName() == "island")
        {
            $form->addButton("Island Manager");
            $form->addButton("Island Info");

        }
        $form->addButton("Teleport");
        $form->addButton("Achievement");
        $form->addButton("Shop");
        $form->addButton("VipItem Shop");
        $form->addButton("Sell All Inventory");
        $form->addButton("Coin");
        $form->addButton("VIP");
        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber"]))
        {
            $form->addButton("RankColor");
        }
        $form->addButton("GUI Mode");
        $form->setTitle("Island Menu");
        $player->sendForm($form);
    }

    public function GuiMode(Player $player)
    {
        $i = Item::get(Item::PAPER);
        $nbt = $i->getNamedTag();
        $nbt->setByte("menu", 1);
        $nbt->setString("menu-mode", "gui");
        $i->setNamedTag($nbt);
        $i->setCustomName("Island Menu");
        $i->setLore(["Hold and tap to open menu !"]);
        $player->getInventory()->setItem(8, $i);
    }
}