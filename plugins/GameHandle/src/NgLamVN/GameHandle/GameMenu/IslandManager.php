<?php

declare(strict_types=1);

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
                    $this->ReNameForm($player);
                    break;
                case 4:
                    $this->ChangeBiomeForm($player);
                    break;
                case 5:
                    Server::getInstance()->dispatchCommand($player, "is pvp");
                    break;
                default:
                    return;
                    break;
            }
        });
        $form->setTitle("Island Manager");

        $form->addButton("§　§l§cEXIT");
        $form->addButton("§lAdd Helper\nThêm người giúp");
        $form->addButton("§lRemove Helper\nXóa Người giúp");
        $form->addButton("§lRename island\nĐổi tên đảo");
        $form->addButton("§lChange island biome\n Thay đổi hệ sinh thái đảo");
        $plot = MyPlot::getInstance()->getPlotByPosition($player->asPosition());
        if ($plot->pvp == true)
        {
            $form->addButton("§lDisable PvP\nTắt PvP");
        }
        else
        {
            $form->addButton("§lEnable PvP\nBật PvP");
        }

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
        $form->setTitle("§　§lAdd Helper");
        $form->addDropdown("§　Player:", $players);
        $player->sendForm($form);
    }

    public function RemoveHelperForm(Player $player)
    {
        $pos = new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel());
        $plot = MyPlot::getInstance()->getPlotByPosition($pos);
        if ($plot == null)
        {
            $player->sendMessage("§cBạn không đứng trong island");
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
        $form->setTitle("§　§lRemove Helper");
        $form->addDropdown("§　Helper:", $helpers);
        $player->sendForm($form);
    }

    public function ReNameForm (Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if(!isset($data[0])) return;

            $plot = MyPlot::getInstance()->getPlotByPosition($player->asPosition());
            if (($player->getName() == $plot->owner) or $player->isOp())
            {
                $plot->name = $data[0];
                $player->sendMessage("§aIsland Renamed !");
            }
            else
            {
                $player->sendMessage("§cYou don't have permission to rename this island");
            }
        });

        $form->setTitle("§　§lRename island");
        $form->addInput("§　Name", "MyIsland123");
        $player->sendForm($form);
    }

    public function ChangeBiomeForm(Player $player)
    {
        $arr = ["<none>", "PLAINS", "DESERT", "MOUNTAINS", "FOREST", "TAIGA", "SWAMP", "NETHER", "HELL", "ICE_PLAINS"];
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            if ($data[0] == "<none>") return;
            Server::getInstance()->dispatchCommand($player, "is biome ".$data[0]);
        });
        $form->addDropdown("§　Biome:", $arr);
        $form->setTitle("§　§lChange Biome");
        $player->sendForm($form);
    }
}
