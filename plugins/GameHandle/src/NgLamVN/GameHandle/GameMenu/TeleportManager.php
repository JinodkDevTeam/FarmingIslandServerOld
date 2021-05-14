<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;

class TeleportManager
{
    public function __construct(Player $player)
    {
        $this->execute($player);
    }

    public function execute (Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            if ($data == 0) return;
            switch ($data)
            {
                case 1:
                    $this->SpecialIslandForm($player);
                    break;
                case 2:
                    if ($player->getLevel()->getName() !== "island")
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp island ".$player->getName());
                    Server::getInstance()->dispatchCommand($player, "is home");
                    break;
                case 3:
                    $this->WarpForm($player);
                    break;
                case 4:
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp afk ".$player->getName());
                    break;
                case 5:
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp mine ".$player->getName());
                    break;
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Teleport");
        $form->addButton("EXIT");
        $form->addButton("Special Island");
        $form->addButton("My Island\nVề đảo của bạn");
        $form->addButton("Go to another island\nDịch chuyển sang đảo khác");
        $form->addButton("Afk Area\nKhu vực afk");
        $form->addButton("Mine Area\nKhu mine");
        $form->addButton("Comming Soon !!");

        $player->sendForm($form);
    }

    public function WarpForm(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            Server::getInstance()->dispatchCommand($player, "is warp ". $data[0]);
        });
        $form->setTitle("Go to another island");
        $form->addInput("Island ID (X;Z)", "1;2");
        $player->sendForm($form);
    }

    public function SpecialIslandForm(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            switch ($data)
            {
                case 0:
                    Server::getInstance()->dispatchCommand($player, "is warp 0;0");
                case 1:
                    Server::getInstance()->dispatchCommand($player, "is warp -4;1");
                default:
                    return;
            }
        });

        $form->addButton("The Ultra Farmland");
        $form->addButton("The Sea Island\nBy PikarioVN");

        $form->setTitle("Special Island");

        $player->sendForm($form);
    }
}