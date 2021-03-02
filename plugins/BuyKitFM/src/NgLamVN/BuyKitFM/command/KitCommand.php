<?php

namespace NgLamVN\BuyKitFM\command;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\BuyKitFM\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use onebone\pointapi\PointAPI;
use pocketmine\plugin\Plugin;

class KitCommand extends PluginCommand
{
    public $plugin;

    public function __construct(Loader $plugin)
    {
        parent::__construct("kit", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Kits");
        $this->setPermission("buykitfm.kit");
    }

    public function getLoader(): Loader
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("buykitfm.kit"))
        {
            $sender->sendMessage("You not have permission to use this command !");
            return;
        }
        $id = [];
        foreach (array_keys($this->getLoader()->getAllKits()) as $kit)
        {
            array_push($id, $kit);
        }
        $form = new SimpleForm(function (Player $player, $data) use ($id)
        {
            if (!isset($data)) return;
            if (!isset($this->plugin->pd[$player->getName()]))
            {
                $this->confirmForm($player, $id[$data]);
                return;
            }
            if ($this->plugin->pd[$player->getName()] !== $id[$data])
            $this->confirmForm($player, $id[$data]);
            else $this->owned($player);
        });
        foreach (array_keys($this->getLoader()->getAllKits()) as $kit)
        {
            $price = $this->getLoader()->getKit($kit)->getPrice();
            $form->addButton($kit . "\n" . $price . " point");
        }
        $form->setTitle("KITS");
        $sender->sendForm($form);
    }
    public function confirmForm(Player $player, $id)
    {
        $form = new ModalForm(function (Player $player, $data) use ($id)
        {
            if ($data === true)
            {
                $price = $this->getLoader()->getKit($id)->getPrice();
                if (PointAPI::getInstance()->myPoint($player) >= $price)
                {
                    PointAPI::getInstance()->reducePoint($player, $price);
                    $this->getLoader()->getKit($id)->giveItem($player);
                    $this->getLoader()->pd[$player->getName()] = $id;
                    $this->BuyDone($player, $id);
                }
                else
                {
                    $this->BuyFail($player, $id);
                }
            }
        });
        $form->setTitle("Confirm");
        $descriptions = "";
        foreach ($this->getLoader()->getKit($id)->getDescription() as $description)
        {
            $descriptions = $descriptions . "\n" . $description;
        }
        $form->setContent($descriptions);
        $form->setButton1("YES");
        $form->setButton2("NO");
        $player->sendForm($form);
    }

    public function owned (Player $player)
    {
        $form = new CustomForm(function (Player $player, $data){});
        $form->setTitle("Tips");
        $form->addLabel("You already have that kit !");
        $player->sendForm($form);
    }

    public function BuyFail (Player $player, string $kitname)
    {
        // DO Anything you want in here when buy fail

        $form = new CustomForm(function (Player $player, $data){});
        $form->setTitle("Buy Fail");
        $form->addLabel("Not enough coin !");
        $player->sendForm($form);
    }

    public function BuyDone (Player $player, string $kitname)
    {
        // DO Anything you want in here when buy done

        $form = new CustomForm(function (Player $player, $data){});
        $form->setTitle("Success");
        $form->addLabel("Buy Success ! Kit:" . $kitname);
        $player->sendForm($form);
    }
}