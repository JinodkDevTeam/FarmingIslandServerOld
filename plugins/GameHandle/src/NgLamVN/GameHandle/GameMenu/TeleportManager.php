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
                    if ($player->getLevel()->getName() !== "island")
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp island ".$player->getName());
                    Server::getInstance()->dispatchCommand($player, "is home");
                    break;
                case 2:
                    $this->WarpForm($player);
                case 3:
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "mw tp afk ".$player->getName());
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Teleport");
        $form->addButton("EXIT");
        $form->addButton("Your Island");
        $form->addButton("Go to another island");
        $form->addButton("Afk Area");
        $form->addButton("Comming Soon");

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
}