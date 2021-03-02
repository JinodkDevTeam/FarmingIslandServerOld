<?php

/*
 *  ____              _  ___ _   ______ __  __
 * |  _ \            | |/ (_) | |  ____|  \/  |
 * | |_) |_   _ _   _| ' / _| |_| |__  | \  / |
 * |  _ <| | | | | | |  < | | __|  __| | |\/| |
 * | |_) | |_| | |_| | . \| | |_| |    | |  | |
 * |____/ \__,_|\__, |_|\_\_|\__|_|    |_|  |_|
 *               __/ |
 *              |___/
 *     Copyright (c) 2021 NgLamVN
 */

namespace NgLamVN\BuyKitFM;

use NgLamVN\BuyKitFM\command\GiveKitCommand;
use NgLamVN\BuyKitFM\command\KitCommand;
use NgLamVN\BuyKitFM\command\KitEffectCommand;
use NgLamVN\BuyKitFM\command\LockCommand;
use NgLamVN\BuyKitFM\ItemLock\ItemLock;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase
{
    /** @var Config $data */
    public $data, $p;
    public $pd, $kits = [];

    public function onEnable()
    {
        $this->saveResource("kits.yml");
        $this->data = new Config($this->getDataFolder() . "kits.yml", Config::YAML);
        $this->saveResource("players.yml");
        $this->p = new Config($this->getDataFolder() . "players.yml", Config::YAML);
        $this->pd = $this->p->getAll();
        $this->createKit();
        $cmd = $this->getServer()->getCommandMap();
        $il = new ItemLock($this);
        $cmd->register("kit", new KitCommand($this));
        $cmd->register("givekit", new GiveKitCommand($this));
        $cmd->register("kiteffect", new KitEffectCommand($this));
        $cmd->register("itemlock", new LockCommand($this));
    }

    public function onDisable()
    {
        $this->p->setAll($this->pd);
        $this->p->save();
    }

    public function createKit()
    {
        foreach (array_keys($this->data->getAll()) as $kit)
        {
            $this->kits[$kit] = new Kit($kit, $this->data->getAll()[$kit]["items"], $this->data->getAll()[$kit]["effects"], $this->data->getAll()[$kit]["description"], $this->data->getAll()[$kit]["price"]);
        }
    }

    public function getKit(string $name): ?Kit
    {
        if (isset($this->kits[$name]))
        return $this->kits[$name];
        else return null;
    }

    public function getAllKits()
    {
        return $this->kits;
    }

}