<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\Config;

class Sell extends PluginCommand
{
    public Core $plugin;

    public $cfg, $data;

    const VIP_RANK = ["Vip", "VipPlus", "Member", "Youtuber"];

    public function __construct(Core $plugin)
    {
        parent::__construct("sell", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Sell items in your inventory");
        $this->setPermission("gh.sell.use");

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
        if (!$sender->hasPermission("gh.sell.use"))
        {
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("/sell <hand|all>");
            return;
        }
        $cp = 0;

        switch ($args[0])
        {
            case "hand":
                $item = $sender->getInventory()->getItemInHand();
                if ($this->toPrice($item) >= 0)
                {
                    if (in_array($this->getCore()->getPlayerGroupName($sender), self::VIP_RANK))
                    {
                        $price = $this->toPrice($item) + $this->toPrice($item)*(1/10 + $cp/100);
                        EconomyAPI::getInstance()->addMoney($sender, $price);
                        $sender->sendMessage("Sell ".$item->getCount() ." item for ". $price . "xu (+".(10 + $cp)." percent)");
                        $sender->getInventory()->removeItem($item);
                        return;
                    }
                    $price = $this->toPrice($item) + $this->toPrice($item)*($cp/100);
                    EconomyAPI::getInstance()->addMoney($sender, $price);
                    $sender->sendMessage("Sell ". $item->getCount() ." item for ". $price . "xu (+".($cp)." percent)");
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
                if (in_array($this->getCore()->getPlayerGroupName($sender), self::VIP_RANK))
                {
                    $price = $price + $price*(1/10 + $cp/100);

                    $sender->sendMessage("Sell ". $count . " items for " . $price . "xu (+".(10 + $cp)." percent)");
                    EconomyAPI::getInstance()->addMoney($sender, $price);
                    return;
                }
                $price = $price + $price*($cp/100);
                $sender->sendMessage("Sell ". $count . " items for " . $price . "xu (+".($cp)." percent)");
                EconomyAPI::getInstance()->addMoney($sender, $price);
                return;

                break;
        }
    }
}
