<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\GameHandle\CoinSystem\CoinSystem;
use NgLamVN\GameHandle\Core;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;

class VipManager
{
    public function __construct(Player $player)
    {
        $this->execute($player);
    }

    public function getCore(): Core
    {
        return Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle");
    }

    public function execute (Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            if (!isset($data)) return;
            switch ($data)
            {
                case 1:
                    $this->VipForm($player);
                    break;
                case 2:
                case 3:
                    $this->CommingSoon($player);
                    break;
            }
        });
        $form->setTitle("§　§lAdvanced Rank");
        $form->addButton("§　§l§cEXIT");
        $form->addButton("§6§lVIP §r§b[Lifetime]\n§aBuy for 2000 coins");
        $form->addButton("§6§lVIP§a+\n§aBuy for 50000 VNĐ per month");
        $form->addButton("§c§lYou§ftuber");

        $player->sendForm($form);
    }

    public function VipForm (Player $player)
    {
        if ($this->getCore()->getPlayerGroupName($player) == "Vip")
        {
            $player->sendMessage("§eYou already have this rank !");
            return;
        }
        if ($this->getCore()->getPlayerGroupName($player) == "VipPlus")
        {
            $player->sendMessage("§eYou already have this rank !");
            return;
        }
        $form = new CustomForm(function (Player $player, $data)
        {
            $this->BuyVipConfirmForm($player);
        });
        $form->setTitle("§　VIP");
        $form->addLabel("§　Quyền hạn:");
        $form->addLabel("§　- Feed: No ngay lập tức");
        $form->addLabel("§　- Heal: Hồi máu ngay lập tức");
        $form->addLabel("§　- Cây mọc nhanh gấp 2 lần");
        $form->addLabel("§　- Tăng 1/10 giá sell cho tất cả items");
        $form->addLabel("§　- Custom Rank Color");
        $form->addLabel("§　- Quyền hạn có thể thay đổi sau những bảng cập nhật server");

        $player->sendForm($form);
    }

    public function BuyVipConfirmForm(Player $player)
    {
        if (CoinSystem::getInstance()->getCoin($player) < 2000)
        {
            $player->sendMessage("§cYou not have enought coin to buy this rank !");
            return;
        }
        $form = new ModalForm(function (Player $player, $data)
        {
            if ($data == true)
            {
                CoinSystem::getInstance()->reduceCoin($player, 2000);
                Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "setgroup " . $player->getName() . " Vip");
            }
        });
        $form->setTitle("§　§l§eConfirm");
        $form->setContent("§　Bạn có chắc chắn mua rank VIP với giá 2000 coin ?");
        $form->setButton1("§　§l§aYes");
        $form->setButton2("§　§l§cNo");

        $player->sendForm($form);
    }

    public function CommingSoon(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            //NOTHING.
        });
        $form->setTitle("§　§l§bNOTE");
        $form->addLabel("§　Please contact admin to get this rank :)");

        $player->sendForm($form);
    }

}