<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use MyPlot\MyPlot;
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
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
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
        array_push($list, "shop");
        array_push($list, "vip-shop");
        array_push($list, "sell-all");
        array_push($list, "coin");
        array_push($list, "vip");
        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber", "Member"]))
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
                case "gui":
                    $this->GuiMode($player);
                    break;
                case "is-info":
                    $this->IslandInfoForm($player);
                    break;
            }
        });
        if ($player->getLevel()->getName() == "island")
        {
            $form->addButton("Island Manager\nQuản lý đảo");
            $form->addButton("Island Info\nThông tin đảo");

        }
        $form->addButton("Teleport\nDịch chuyển");
        $form->addButton("Shop");
        $form->addButton("VipItem Shop");
        $form->addButton("Sell All Inventory\nBán toàn bộ vật phẩm");
        $form->addButton("Coin");
        $form->addButton("VIP");
        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber", "Member"]))
        {
            $form->addButton("RankColor");
        }
        $form->addButton("GUI Mode\nChuyển sang chế độ GUI");
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

    public function IslandInfoForm(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data) {});
        $form->setTitle("Island Info");
        $plot = MyPlot::getInstance()->getPlotByPosition($player->asPosition());
        $h = "";
        foreach ($plot->helpers as $helper)
        {
            if ($h == "")
            {
                $h = $h . "" . $helper;
            } else {
                $h = $h . "," . $helper;
            }
        }
        $form->addLabel("Island ID: " . $plot->X . ";" . $plot->Z);
        $form->addLabel("Owner: " . $plot->owner);
        $form->addLabel("Island Name: " . $plot->name);
        $form->addLabel("Helpers: " . $h);

        $player->sendForm($form);
    }
}