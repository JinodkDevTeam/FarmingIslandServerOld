<?php

use NgLamVN\GameHandle\Core;

use pocketmine\utils\Config;

class AchivementManager
{
    public $core;
    public static $instance;

    public function __construct(Core $plugin)
    {
        $this->core = $plugin;
        self::$instance = $this;
    }

    public function getCore(): Core
    {
        return $this->core;
    }

    public static function getInstance(): AchivementManager
    {
        return self::$instance;
    }

    public function getCoinDataFolder()
    {
        $folder = $this->getCore()->getDataFolder() . "Achivement/";
        if (!file_exists($folder))
        {
            @mkdir($folder);
        }
        return $folder;
    }
    public function saveData()
    {
        $this->cfg->setAll($this->data);
        $this->cfg->save();
    }

    public function getData()
    {
        $this->cfg = new Config($this->getCoinDataFolder() . "data.yml", Config::YAML);
        $this->data = $this->cfg->getAll();
    }

}