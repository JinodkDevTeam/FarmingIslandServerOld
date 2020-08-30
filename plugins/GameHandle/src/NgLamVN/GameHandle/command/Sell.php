<?php

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class Sell extends PluginCommand
{
    public $plugin;

    public $cfg, $data;

    public function __construct(Core $plugin)
    {
        parent::__construct("sell", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Sell items in your inventory");
        $this->setPermission("sell.use");

        $this->getDataConfig();
    }

    public function getCore(): Core
    {
        return $this->plugin;
    }

    public function getSellDataFolder()
    {
        $folder = $this->getCore()->getDataFolder() . "Sell/";
        if (!file_exists($folder))
        {
            @mkdir($folder);
        }
        return $folder;
    }

    public function getDataConfig()
    {
        $this->cfg = new Config($this->getSellDataFolder() . "sell.yml", Config::YAML);
        $this->data = $this->cfg->getAll();
    }
    public function toPrice (Item $item)
    {
        $id = $item->getId();
        $meta = $item->getDamage();
        $count = $item->getCount();
        if ($meta !== 0)
        {
            $pos = $id . "/" . $meta;
        }
        else
        {
            $pos = $id;
        }
        if (!isset($this->data[$pos]))
        {
            return -1;
        }
        return $this->data[$pos] * $count;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("sell.use"))
        {
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("/sell <hand|all>");
            return;
        }
        switch ($args[0])
        {
            case "hand":
                $item = $sender->getInventory()->getItemInHand();
                if ($this->toPrice($item) >= 0)
                {
                    if ($this->getCore()->getPlayerGroupName($sender) == "Vip")
                    {
                        EconomyAPI::getInstance()->addMoney($sender, $this->toPrice($item) + $this->toPrice($item)*(1/10));
                        $sender->sendMessage("Sell ".$item->getCount() ." item for ". $this->toPrice($item) . "xu (+ 1/10)");
                        $sender->getInventory()->removeItem($item);
                        return;
                    }
                    EconomyAPI::getInstance()->addMoney($sender, $this->toPrice($item));
                    $sender->sendMessage("Sell ". $item->getCount() ." item for ". $this->toPrice($item) . "xu");
                    $sender->getInventory()->removeItem($item);
                }
                else
                {
                    $sender->sendMessage("Cannot sell this item");
                }
                break;
            case "all":
                $items = $sender->getInventory()->getContents();
                $count = 0;
                $price = 0;
                foreach ($items as $item)
                {
                    if ($this->toPrice($item) >= 0)
                    {
                        $count = $count + $item->getCount();
                        $price = $price + $this->toPrice($item);
                        $sender->getInventory()->removeItem($item);
                    }
                }
                if ($this->getCore()->getPlayerGroupName($sender) == "Vip")
                {
                    $price = $price + $price*(1/10);

                    $sender->sendMessage("Sell ". $count . " items for " . $price . " xu (x 1/10)");
                    EconomyAPI::getInstance()->addMoney($sender, $price);
                    return;
                }
                $sender->sendMessage("Sell ". $count . " items for " . $price . " xu");
                EconomyAPI::getInstance()->addMoney($sender, $price);
                return;

                break;
        }
    }
}
