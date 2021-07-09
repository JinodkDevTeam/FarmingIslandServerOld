<?php

namespace NgLamVN\CustomStuff;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;
use NgLamVN\CustomStuff\block\__initBlock;
use NgLamVN\CustomStuff\item\__init;
use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\plugin\PluginBase;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class CustomStuff extends PluginBase
{

    public $piggyCE;

    public function onEnable()
    {
        $init = new __init($this);
        $blocinit = new __initBlock($this);

        $this->piggyCE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");

        $edian = new BigEndianNBTStream();
        $item = Item::get(Item::IRON_CHESTPLATE);
        $item->setCustomName("§r§　§l§cNo §eYou");
        $nbt = $item->getNamedTag();
        $nbt->setString("CustomItem", "NoYouArmor");
        $nbt->setByte("Unbreakable", 1);
        $item->setNamedTag($nbt);
        /*$this->enchantItem($item, 5, "thorns");
        $item->setLore(["§r§fWhat cactus can do ? \n\n§f§lUnbreakable"]);*/
        /*$item->setNamedTagEntry(new ListTag(Item::TAG_ENCH, [], NBT::TAG_Compound));*/
        $data = bin2hex($edian->writeCompressed($item->nbtSerialize()));
        $this->getLogger()->info($data);
    }

    /*public function enchantItem(Item $item, int $level, $enchantment): void
    {
        if(is_string($enchantment)){
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
    }*/
}
