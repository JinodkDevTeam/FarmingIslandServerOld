<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class IslandManager
{
    public function __construct(Player $player)
    {
        $this->execute($player);
    }

    public function execute(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            switch ($data)
            {
                case 1:
                    Server::getInstance()->dispatchCommand($player, "is auto");
                    break;
                case 2:
                    $this->AddHelperForm($player);
                    break;
                case 3:
                    $this->RemoveHelperForm($player);
                    break;
                case 4:
                    $this->WarpForm($player);
                    break;
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Island Manager");

        $form->addButton("Exit");
        $form->addButton("Create Island");
        $form->addButton("Add Helper");
        $form->addButton("Remove Helper");
        $form->addButton("Go to another island");

        $player->sendForm($form);
    }

    public function AddHelperForm(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            Server::getInstance()->dispatchCommand($player, "is addhelper ". $data[0]);
        });
        $form->setTitle("Add Helper");
        $form->addInput("Player Name", "Steve123");
        $player->sendForm($form);
    }

    public function RemoveHelperForm(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            Server::getInstance()->dispatchCommand($player, "is removehelper ". $data[0]);
        });
        $form->setTitle("Remove Helper");
        $form->addInput("Player Name", "Steve123");
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
