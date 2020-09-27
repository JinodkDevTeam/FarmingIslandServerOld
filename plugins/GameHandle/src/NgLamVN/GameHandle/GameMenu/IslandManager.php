<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use MyPlot\MyPlot;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Position;

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
                    $this->AddHelperForm($player);
                    break;
                case 2:
                    $this->RemoveHelperForm($player);
                    break;
                case 3:
                    $this->WarpForm($player);
                    break;
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Island Manager");

        $form->addButton("Exit");
        $form->addButton("Add Helper");
        $form->addButton("Remove Helper");
        $form->addButton("Go to another island");

        $player->sendForm($form);
    }

    public function AddHelperForm(Player $player)
    {
        $players = ["<None>"];
        foreach (Server::getInstance()->getOnlinePlayers() as $p)
        {
            array_push($players, $p->getName());
        }

        $form = new CustomForm(function (Player $player, $data) use ($players)
        {
            if (!isset($data[0])) return;
            $pname = $players[$data[0]];
            if ($pname == "<None>")
            {
                return;
            }
            Server::getInstance()->dispatchCommand($player, "is addhelper ". $pname);
        });
        $form->setTitle("Add Helper");
        $form->addDropdown("Player:", $players);
        $player->sendForm($form);
    }

    public function RemoveHelperForm(Player $player)
    {
        $pos = new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel());
        $plot = MyPlot::getInstance()->getPlotByPosition($pos);
        if ($plot == null)
        {
            $player->sendMessage("Bạn không đứng trong island");
            return;
        }
        $helpers = ["<None>"];
        foreach ($plot->helpers as $h)
        {
            array_push($helpers, $h);
        }
        $form = new CustomForm(function (Player $player, $data) use ($helpers)
        {
            if (!isset($data[0])) return;
            $pname = $helpers[$data[0]];
            if ($pname == "<None>")
            {
                return;
            }
            Server::getInstance()->dispatchCommand($player, "is removehelper ". $pname);
        });
        $form->setTitle("Remove Helper");
        $form->addDropdown("Helpers:", $helpers);
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
