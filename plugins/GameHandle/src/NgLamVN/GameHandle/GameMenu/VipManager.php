<?php

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
        return Server::getInstance()->getPluginManager()->getPlugin("GameHandle");
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
        $form->setTitle("Advanced Rank");
        $form->addButton("EXIT");
        $form->addButton("§6§lVIP §r§b[Lifetime]\n§aBuy for 2000 coins");
        $form->addButton("§6§lVIP§a+\n§aBuy for 50000 VNĐ per month");
        $form->addButton("§c§lYou§ftuber");

        $player->sendForm($form);
    }

    public function VipForm (Player $player)
    {
        if ($this->getCore()->getPlayerGroupName($player) == "Vip")
        {
            $player->sendMessage("You already have this rank !");
            return;
        }
        if ($this->getCore()->getPlayerGroupName($player) == "VipPlus")
        {
            $player->sendMessage("You already have this rank !");
            return;
        }
        $form = new CustomForm(function (Player $player, $data)
        {
            $this->BuyVipConfirmForm($player);
        });
        $form->setTitle("VIP");
        $form->addLabel("Quyền hạn:");
        $form->addLabel("- Feed: No ngay lập tức");
        $form->addLabel("- Heal: Hồi máu ngay lập tức ");
        $form->addLabel("- Tăng 1/10 giá sell cho tất cả items");
        $form->addLabel("- Custom Rank Color");
        $form->addLabel("Quyền hạn khác có thể có sau những bảng cập nhật server");

        $player->sendForm($form);
    }

    public function BuyVipConfirmForm(Player $player)
    {
        if (CoinSystem::getInstance()->getCoin($player) < 2000)
        {
            $player->sendMessage("You not have enought coin to buy this rank !");
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
        $form->setTitle("Confirm");
        $form->setContent("Bạn có chắc chắn mua rank VIP với giá 2000 coin ?");
        $form->setButton1("Yes");
        $form->setButton2("No");

        $player->sendForm($form);
    }

    public function CommingSoon(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {

        });
        $form->setTitle("NOTE");
        $form->addLabel("Please contact admin to get this rank :)");

        $player->sendForm($form);
    }

}