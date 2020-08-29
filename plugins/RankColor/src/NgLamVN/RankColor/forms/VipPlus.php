<?php

namespace NgLamVN\RankColor\forms;

use jojoe77777\FormAPI\SimpleForm;

use NgLamVN\RankColor\RankColor;
use pocketmine\Player;

class VipPlus
{
    public $colors = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"];
    public $formats = [];

    private $plugin;

    public function __construct(RankColor $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->createColor();
        $this->execute($player);
    }

    public function createColor()
    {
        foreach ($this->colors as $color)
        {
            foreach ($this->colors as $color2)
            {
                $format = "§f|§l§" . $color . "VIP§" . $color2 . "+§r§f|";
                array_push($this->formats, $format);
            }
        }
    }

    public function execute (Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            if ($data == 0) return;
            $this->plugin->setColor($player, $this->formats[$data-1]);
            $player->sendMessage("Color Changed");
        });
        $form->setTitle("Change Rank Color");
        $form->addButton("Exit");
        foreach ($this->formats as $format)
        {
            $form->addButton("§f◣".$format. "§f◥");
        }
        $form->sendToPlayer($player);
    }


}