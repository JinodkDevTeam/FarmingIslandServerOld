<?php

namespace NgLamVN\GameHandle\GameMenu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use NgLamVN\GameHandle\Core;
use NgLamVN\GameHandle\task\DelayTask;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

use MyPlot\MyPlot;

class GuiMenu
{

    public function __construct(Player $player)
    {
        $this->menuForm($player);
    }

    public function getCore(): ?Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
    }

    public function menuForm(Player $player)
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->setName("Island Menu");
        $menu->setListener(InvMenu::readonly(\Closure::fromCallable([$this, "MenuListener"])));
        $inv = $menu->getInventory();

        $item2 = Item::get(Item::PAPER);
        $item2->setCustomName("Coin");
        $inv->setItem(12, $item2);

        $item3 = Item::get(Item::ENDER_PEARL);
        $item3->setCustomName("Teleport");
        $inv->setItem(14, $item3);

        if ($player->getLevel()->getName() == "island")
        {
            $item1 = Item::get(Item::BOOK);
            $item1->setCustomName("Island Manager");
            $inv->setItem(13, $item1);

            $item4 = Item::get(Item::PAPER);
            $item4->setCustomName("Island Info:");
            $pos = new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel());
            $plot = MyPlot::getInstance()->getPlotByPosition($pos);
            if ($plot !== null)
            {
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
                $lore = [
                    "Island ID: " . $plot->X . ";" . $plot->Z,
                    "Owner: " . $plot->owner,
                    "Island Name: " . $plot->name,
                    "Helpers: " . $h,
                ];
                $item4->setLore($lore);
                $inv->setItem(0, $item4);
            }
        }

        $item5 = Item::get(Item::GOLD_INGOT);
        $item5->setCustomName("VIP");
        $inv->setItem(26,$item5);

        $item6 = Item::get(Item::INFO_UPDATE);
        $item6->setCustomName("Sell All Inventory !");
        $inv->setItem(22, $item6);

        $item7 = Item::get(Item::EMERALD);
        $item7->setCustomName("Shop");
        $inv->setItem(4, $item7);

        if (in_array($this->getCore()->getPlayerGroupName($player), ["Vip", "VipPlus", "Staff", "Admin", "Youtuber"]))
        {
            $item8 = Item::get(Item::DYE, 11, 1);
            $item8->setCustomName("RankColor");
            $inv->setItem(18, $item8);
        }

        $item9 = Item::get(Item::DIAMOND);
        $item9->setCustomName("Achievements");
        $inv->setItem(8, $item9);

        $item10 = Item::get(Item::NETHER_STAR);
        $item10->setCustomName("VipItem Shop");
        $inv->setItem(11, $item10);

        $item11 = Item::get(Item::OBSIDIAN);
        $item11->setCustomName("???");
        $item11->setLore(["Comming Soon !"]);
        $inv->setItem(15, $item11);

        $item12 = Item::get(Item::PAINTING);
        $item12->setCustomName("UI mode");
        $inv->setItem(17, $item12);

        $menu->send($player);
    }

    public function menuListener(DeterministicInvMenuTransaction $transaction)
    {
        $player = $transaction->getPlayer();
        $itemClicked = $transaction->getItemClicked();
        $itemClickedWith = $transaction->getItemClickedWith();
        $action = $transaction->getAction();
        $invTransaction = $transaction->getTransaction();
        $player->removeWindow($action->getInventory());
        $slot = $action->getSlot();

        $transaction->then(function() use ($player, $slot)
        {
            $this->menuHandle($player, $slot);
        });
    }

    public function menuHandle(Player $player, $slot)
    {
        switch ($slot)
        {
            case 12:
                Server::getInstance()->dispatchCommand($player, "coin");
                break;
            case 13:
                return new IslandManager($player);
                break;
            case 14:
                return new TeleportManager($player);
                break;
            case 18:
                Server::getInstance()->dispatchCommand($player, "rankcolor");
                break;
            case 26:
                return new VipManager($player);
                break;
            case 4:
                Server::getInstance()->dispatchCommand($player, "shop");
                break;
            case 22:
                Server::getInstance()->dispatchCommand($player, "sell all");
                break;
            case 11:
                new DelayTask(100, function () use ($player)
                {
                    Server::getInstance()->dispatchCommand($player, "cuahang");
                }, $this->getCore(), $player);
                break;
            case 8:
                Server::getInstance()->dispatchCommand($player, "achievement");
                break;
            case 17:
                $this->UiMode($player);
                break;
        }
    }

    public function UiMode(Player $player)
    {
        $i = Item::get(Item::PAPER);
        $nbt = $i->getNamedTag();
        $nbt->setByte("menu", 1);
        $nbt->setString("menu-mode", "ui");
        $i->setNamedTag($nbt);
        $i->setCustomName("Island Menu");
        $i->setLore(["Hold and tap to open menu !"]);
        $player->getInventory()->setItem(8, $i);
    }
}