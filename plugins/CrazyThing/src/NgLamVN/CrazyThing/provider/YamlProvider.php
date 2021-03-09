<?php

namespace NgLamVN\CrazyThing\provider;

use pocketmine\utils\Config;

class YamlProvider
{
    public $configdata, $configsetting, $data, $setting;

    public function __construct()
    {
    }

    public function open(string $path)
    {
        $this->configdata = new Config($path . "data.yml", Config::YAML);
        $this->configsetting = new Config($path . "setting.yml", Config::YAML);
        $this->data = $this->configdata->getAll();
        $this->setting = $this->configsetting->getAll();
    }

    public function close ()
    {
        $this->configdata->setAll($this->data);
        $this->configsetting->setAll($this->setting);
        $this->configdata->save();
        $this->configsetting->save();
    }

    public function getAllData(): array
    {
        return $this->data;
    }

    public function getAllSetting(): array
    {
        return $this->setting;
    }

    public function setAllData(array $data)
    {
        $this->data = $data;
    }

    public function setAllSetting (array $setting)
    {
        $this->setting = $setting;
    }

    public function getAllBanData(): array
    {
        return $this->data["ban"];
    }

    public function setAllBanData(array $data)
    {
        $this->data["ban"] = $data;
    }
}