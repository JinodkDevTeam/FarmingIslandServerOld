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
        array_push($list, "tutorial");
        array_push($list, "invcraft");
        array_push($list, "event");
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
                case "tutorial":
                    Server::getInstance()->dispatchCommand($player, "tutorial");
                    break;
                case "invcraft":
                    Server::getInstance()->dispatchCommand($player, "invcraft");
                    break;
                case "event":
                    Server::getInstance()->dispatchCommand($player, "event");
                    break;
            }
        });
        if ($player->getLevel()->getName() == "island")
        {
            $form->addButton("§　§lIsland Manager\nQuản lý đảo");
            $form->addButton("§　§lIsland Info\nThông tin đảo");

        }
        $form->addButton("§lTeleport\nDịch chuyển");
        $form->addButton("§　§lShop");
        $form->addButton("§　§lVipItem Shop");
        $form->addButton("§lSell All Inventory\nBán toàn bộ vật phẩm");
        $form->addButton("§　§lCoin");
        $form->addButton("§　§lVIP");
        $form->addButton("§lTutorial\nXem cách chơi");
        $form->addButton("§lInvCraft\nBàn chế tạo siêu to khổng lồ");
        $form->addButton("§　§l§eEVENT §0INFO");
        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber", "Member"]))
        {
            $form->addButton("§　§lRankColor");
        }
        $form->addButton("§lGUI Mode\nChuyển sang chế độ GUI");
        $form->setTitle("§　Island Menu");
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
        $form->setTitle("§　§lIsland Info");
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
        $form->addLabel("§　Island ID: " . $plot->X . ";" . $plot->Z);
        $form->addLabel("§　Owner: " . $plot->owner);
        $form->addLabel("§　Island Name: " . $plot->name);
        $form->addLabel("§　Helpers: " . $h);

        $player->sendForm($form);
    }
}