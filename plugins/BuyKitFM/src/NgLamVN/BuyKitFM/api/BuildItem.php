<?php

namespace NgLamVN\BuyKitFM\api;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class BuildItem
{
    public $piggyCE;

    public function __construct()
    {
        $this->piggyCE = Server::getInstance()->getPluginManager()->getPlugin("PiggyCustomEnchants");
    }

    public function make(array $info): Item
    {
        if (!isset($info["id"])) throw new \Exception("Missed something in config");
        $item = $this->makeFormId($info["id"]);
        if (isset($info["name"])) $item->setCustomName($info["name"]);
        if (isset($info["enchants"]))
        {
            foreach (array_keys($info["enchants"]) as $enchant)
            {
                $ide = $info["enchants"][$enchant]["id"];
                $lve = $info["enchants"][$enchant]["lvl"];
                $this->enchantItem($item, $lve, $ide);
            }
        }
        if (isset($info["lore"]))
        {
            $item->setLore($info["lore"]);
        }
        if (isset($info['unbreakable']))
        {
            if ($info['unbreakable'] == "true")
            {
                $nbt = $item->getNamedTag();
                $nbt->setByte("Unbreakable", 1);
                $item->setNamedTag($nbt);
            }
        }

        return $item;
    }

    public function makeFormId($id): Item
    {
        $exn = explode(':', $id);
        $idi = $exn[0];
        $metai = $exn[1];
        $counti = $exn[2];
        $item = Item::get($idi, $metai, $counti);
        return $item;
    }

    public function enchantItem(Item $item, int $level, $enchantment): void
    {
        if(is_string($enchantment))
        {
            $ench = Enchantment::getEnchantmentByName((string) $enchantment);
            if($this->piggyCE !== null && $ench === null){
                $ench = CustomEnchantManager::getEnchantmentByName((string) $enchantment);
            }
            if($this->piggyCE !== null && $ench instanceof CustomEnchantManager){
                $this->piggyCE->addEnchantment($item, $ench->getName(), (int) $level);
            }else{
                $item->addEnchantment(new EnchantmentInstance($ench, (int) $level));
            }
        }
        if(is_int($enchantment)){
            $ench = Enchantment::getEnchantment($enchantment);
            $item->addEnchantment(new EnchantmentInstance($ench, (int) $level));
        }
    }
}