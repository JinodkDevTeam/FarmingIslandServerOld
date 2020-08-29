<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\SimpleForm;
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
                    Server::getInstance()->dispatchCommand($player, "is home");
                    break;
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Teleport");
        $form->addButton("EXIT");
        $form->addButton("Your Island");
        $form->addButton("Comming Soon");

        $player->sendForm($form);
    }
}