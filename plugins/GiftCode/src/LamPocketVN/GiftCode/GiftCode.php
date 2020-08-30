<?php

namespace LamPocketVN\GiftCode;

use pocketmine\block\RedstoneTorchUnlit;
use pocketmine\item\StringItem;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\utils\Config;
use pocketmine\{Player, Server};

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;

use LamPocketVN\GiftCode\Form;

class GiftCode extends PluginBase
{
    public $type, $setting, $giftcode;

    public $code;

    public $form, $piggyCE;

    public function getType()
    {
        return $this->type->getAll();
    }
    public function getSetting()
    {
        return $this->setting->getAll();
    }
    public function getAllType()
    {
        $types = [];
        foreach (array_keys($this->getType()) as $type)
        {
            $lol = array_push($types, $type);
        }
        return $types;
    }

    public function onEnable()
    {
        $this->piggyCE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
        if (!file_exists($this->getDataFolder()))
        {
            mkdir($this->getDataFolder());
        }
        $this->saveResource("type.yml");
        $this->saveResource("setting.yml");
        $this->type = new Config($this->getDataFolder() . "type.yml", Config::YAML);
        $this->setting = new Config($this->getDataFolder() . "setting.yml", Config::YAML);
        $this->giftcode = new Config($this->getDataFolder() . "giftcode.yml", Config::YAML);
        $this->code = $this->giftcode->getAll();

        $this->form = new Form($this);

        $this->getLogger()->info("GiftCode Enabled");
    }

    public function onDisable()
    {
        $this->giftcode->setAll($this->code);
        $this->giftcode->save();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName()))
        {
            case "giftcode":
                if ($sender->hasPermission("giftcode.setting"))
                {
                    $this->form->ManagerForm($sender);
                }
                else
                {
                    $this->form->normalForm($sender);
                }
                return true;
                break;
        }
    }
    public function IsExist ($code)
    {
        if (isset($this->code[$code]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function genCode($type)
    {
        $gen = md5(uniqid(mt_rand(0,32767)));
        $code = $this->getSetting()['prefix'] . strtoupper(substr($gen, 0, $this->getSetting()['lenght']));
        if ($this->IsExist($code))
        {
            while (IsExist($code))
            {
                $gen = md5(uniqid(mt_rand(0,32767)));
                $code = $this->getSetting()['prefix'] . strtoupper(substr($gen, 0, $this->getSetting()['lenght']));
            }
        }
        $this->code[$code] = $type;
        return $code;
    }

    /** ==================================================================== */

    public function Reward(Player $player ,$type)
    {
        if (isset($this->getType()[$type]['item']))
        {
            foreach (array_keys($this->getType()[$type]['item']) as $id)
            {
                $ext = explode(":", $this->getType()[$type]['item'][$id]['id']);
                $item = Item::get($ext[0], $ext[1], $ext[2]);
                if (isset($this->getType()[$type]['item'][$id]['name']))
                {
                    $item->setCustomName($this->getType()[$type]['item'][$id]['name']);
                }
                if (isset($this->getType()[$type]['item'][$id]['lore']))
                {
                    $item->setLore([$this->getType()[$type]['item'][$id]['lore']]);
                }
                if (isset($this->getType()[$type]['item'][$id]['enchantment']))
                {
                    foreach (array_keys($this->getType()[$type]['item'][$id]['enchantment']) as $ec)
                    {
                        $this->enchantItem($item, $this->getType()[$type]['item'][$id]['enchantment'][$ec]['lvl'], $this->getType()[$type]['item'][$id]['enchantment'][$ec]['id']);
                    }
                }
                $player->getInventory()->addItem($item);
            }
        }
        if (isset($this->getType()[$type]['command']))
        {
            foreach (array_keys($this->getType()[$type]['command']) as $command)
            {
                $cmd = str_replace("{player}", $player->getName(), $this->getType()[$type]['command'][$command]);
                $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "$cmd");
            }
        }
    }
    public function enchantItem(Item $item, int $level, $enchantment): void
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
    }
}