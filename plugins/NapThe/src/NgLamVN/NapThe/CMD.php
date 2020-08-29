<?php

namespace NgLamVN\NapThe;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class CMD extends PluginCommand
{
    public $plugin;

    public function __construct(NapThe $plugin)
    {
        parent::__construct("napthe", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Nap the");
        $this->setPermission("napthe.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $test = false;
        if (isset($args[0]))
        {
            if ($args[0] == "test")
            {
                $test = true;
            }
        }

        $form = new SimpleForm(function (Player $player, $data) use ($test)
        {
            if ($data == null) return;

            switch ($data)
            {
                case 1:
                    $this->NapTheForm($player, $test);
                    break;
                case 2:
                    $this->HistoryForm($player);
                    break;
            }
        });

        $form->setTitle("NAP THE");
        $form->addButton("EXIT");
        $form->addButton("NAP THE");
        $form->addButton("LICH SU NAP THE");

        $sender->sendForm($form);
    }

    public function NapTheForm(Player $player, $test = false)
    {
        $mg = [
            "10000",
            "20000",
            "50000",
            "100000",
            "200000",
            "500000",
            "1000000"
        ];
        $lt = [
            "Viettel",
            "Mobifone",
            "Vinaphone",
            "Zing",
            "Gate",
            "VCoin"
        ];


        $form = new CustomForm(function (Player $player, $data) use ($mg, $test, $lt)
        {
            if (!isset($data[0])) return;
            if (!isset($data[1])) return;
            if (!isset($data[2])) return;
            if (!isset($data[3])) return;
            if ($data[0] == "") return;
            if ($data[1] == "") return;

            if ($test == true)
            {
                $player->sendMessage("Test mode:");
                $player->sendMessage("Ma the:" . $data[0]);
                $player->sendMessage("Seri: " . $data[1]);
                $player->sendMessage("Mang:" . $lt[$data[3] + 1]);
                $player->sendMessage("Menh gia:" . $mg[$data[2]]);
                return;
            }

            $this->plugin->XuLy($player, $data[3] + 1, $mg[$data[2]], $data[0], $data[1]);
        });

        $form->setTitle("Nap The");
        $form->addInput("Ma the", "123456789");
        $form->addInput("Seri", "123456789");
        $form->addDropdown("Menh gia", $mg);
        $form->addDropdown("Loai the", $lt);
        $player->sendForm($form);
    }

    public function HistoryForm (Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            return;
        });

        $form->setTitle("Lich su nap the");

        if (isset($this->plugin->data[$player->getName()]))
        {
            $datas = $this->plugin->data[$player->getName()];
            foreach (array_keys($datas) as $data) {
                $form->addLabel("Loai the:" . $datas[$data]["card_id"]);
                $form->addLabel("Menh Gia:" . $datas[$data]["card_value"]);
                $form->addLabel("Ma the:" . $datas[$data]["pin_field"]);
                $form->addLabel("Seri:" . $datas[$data]["seri_field"]);
                $form->addLabel("Tinh trang:" . $datas[$data]["status"]);
                $form->addLabel("");
            }
        }
        $player->sendForm($form);
    }

}
