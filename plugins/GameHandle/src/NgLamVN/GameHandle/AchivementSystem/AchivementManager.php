<?php


/**
 * WHY I USE Achivement and not "Achievement" ????
 * Pocketmine haz use that name so I dont want it cause error.
 */

namespace NgLamVN\GameHandle\AchivementSystem;

use NgLamVN\GameHandle\AchivementSystem\type\BreakType;
use NgLamVN\GameHandle\AchivementSystem\type\FishType;
use NgLamVN\GameHandle\AchivementSystem\type\PlaceType;
use NgLamVN\GameHandle\Core;

use pocketmine\utils\Config;
use NgLamVN\GameHandle\AchivementSystem\Achivement;
use NgLamVN\GameHandle\AchivementSystem\AchivementData;

class AchivementManager
{
    public $core;
    public static $instance;
    public $data;
    public $cfg, $cfg2;
    public $playerdata, $pdatas;

    public $achivements;

    public function __construct(Core $plugin)
    {
        $this->core = $plugin;
        self::$instance = $this;
    }

    /**
     * @return Core
     */
    public function getCore(): Core
    {
        return $this->core;
    }

    /**
     * @return AchivementManager
     */
    public static function getInstance(): AchivementManager
    {
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getAchivementDataFolder()
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
        $this->cfg2->setAll($this->playerdata);
        $this->cfg2->save();
    }

    public function getData()
    {
        $this->cfg = new Config($this->getAchivementDataFolder() . "data.yml", Config::YAML);
        $this->data = $this->cfg->getAll();
        $this->cfg2 = new Config($this->getAchivementDataFolder() . "player.yml", Config::YAML);
        $this->playerdata = $this->cfg2->getAll();
    }

    public function registerAchivement()
    {
        $this->achivements = [];
        foreach (array_keys($this->data) as $id)
        {
            $name = $this->data[$id]["name"];
            $type = $this->data[$id]["type"];
            $item = $this->data[$id]["item"];
            $des = $this->data[$id]["description"];
            $level = $this->data[$id]["level"];

            array_push($this->achivements, new Achivement($name, $id, $type, $level, $item, $des));
        }

    }
    public function registerPlayerData()
    {
        $this->pdatas = [];
        foreach (array_keys($this->pdatas) as $name)
        {
            $playername = $name;
            $a_data = $this->playerdata[$name];

            array_push($this->pdatas, new AchivementData($playername, $a_data));
        }
    }

    public function registerEvent()
    {
        foreach ($this->getAllAchivement() as $ac)
        {
            switch ($ac->getType())
            {
                case "break":
                    $this->getCore()->getServer()->getPluginManager()->registerEvents(new BreakType($ac), $this->getCore());
                    break;
                case "place":
                    $this->getCore()->getServer()->getPluginManager()->registerEvents(new PlaceType($ac), $this->getCore());
                    break;
                case "fish":
                    $this->getCore()->getServer()->getPluginManager()->registerEvents(new FishType($ac), $this->getCore());
                    break;
            }
        }
    }

    /**
     *  API FUNCTIONS
     */

    /**
     * @return Achivement[]
     */
    public function getAllAchivement(): array
    {
        return $this->achivements;
    }

    /**
     * @param int $id
     * @return Achivement
     */
    public function getAchivement(int $id): Achivement
    {
        return $this->achivements[$id];
    }

    /**
     * @param string $playername
     * @return AchivementData
     */
    public function getPlayerData(string $playername)
    {
        return new AchivementData($playername, $this->playerdata[$playername]);
    }

    /**
     * @return AchivementData[]
     */
    public function getAllPlayerData()
    {
        return $this->pdatas;
    }

}