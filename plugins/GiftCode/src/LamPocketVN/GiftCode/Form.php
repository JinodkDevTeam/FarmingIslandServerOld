<?php

namespace LamPocketVN\GiftCode;

use pocketmine\{Player, Server};

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

use LamPocketVN\GiftCode\GiftCode;

class Form
{
    /**
     * @var $plugin
     */
    private $plugin;

    public function __construct(GiftCode $plugin)
    {
        $this->plugin = $plugin;
    }

    public function normalForm(Player $player)
    {
        $form = new CustomForm(
            function ($player, $data)
            {
                $code = $data[0];
                if ($this->plugin->IsExist($code))
                {
                    $this->plugin->Reward($player, $this->plugin->code[$code]);
                    unset($this->plugin->code[$code]);
                }
                else
                {
                    $player->sendMessage($this->plugin->getSetting()['msg']['wrong-giftcode']);
                }
            }
        );
        $form->setTitle($this->plugin->getSetting()['form']['title']);
        $form->addInput($this->plugin->getSetting()['form']['input'], "1A2B3C4D5E6F7G8H");
        $form->sendToPlayer($player);
    }
    public function ManagerForm (Player $player)
    {
        $form = new SimpleForm(
            function ($player, $data)
            {
                switch ($data)
                {
                    case 1:
                        $this->normalForm($player);
                        break;
                    case 2:
                        $this->genForm($player);
                        break;
                    case 3:
                        $this->deleteForm($player);
                        break;
                    case 4:
                        $this->listForm($player);
                        break;
                }
            }
        );
        $form->setTitle("GiftCode Manager");
        $form->addButton("Exit");
        $form->addButton("Use GiftCode");
        $form->addButton("Add GiftCode");
        $form->addButton("Delete GiftCode");
        $form->addButton("List GiftCode");
        $form->sendToPlayer($player);
    }
    public function genForm (Player $player)
    {
        $form = new CustomForm(
            function (Player $player, $data)
            {
                if (!isset($data[0]))
                {
                    return;
                }
                $amount = $data[0];
                $type = $this->plugin->getAllType()[$data[1]];
                if (!is_numeric($amount))
                {
                    $player->sendMessage("Amount must be numeric !");
                    return true;
                }
                $this->genResult($player, $amount, $type);
            }
        );
        $form->setTitle("Add GiftCode");
        $form->addInput("Amount", 123123);
        $form->addDropdown("Type", $this->plugin->getAllType());
        $form->sendToPlayer($player);
    }
    public function genResult($player, $amount, $type)
    {
        $form = new CustomForm(
            function ($player, $data)
            {
            }
        );
        $form->setTitle("Generate Result");
        for ($i = 1; $i <= $amount; $i++)
        {
            $form->addLabel($this->plugin->genCode($type));
        }
    }
    public function deleteForm ($player)
    {
        $form = new CustomForm(
            function (Player $player, $data)
            {
                if (!isset($data[0]))
                {
                    return;
                }
                if (!$this->plugin->IsExist($data[0]))
                {
                    $player->sendMessage("GiftCode not exist !");
                }
                unset($this->plugin->code[$data[0]]);
                $player->sendMessage("Deleted code " . $data[0]);
            }
        );
        $form->setTitle("Delete GiftCode");
        $form->addInput("Code", "1A2B3C4D5E");
        $form->sendToPlayer($player);
    }
    public function listForm (Player $player)
    {
        $form = new CustomForm(
            function ($player, $data)
            {
            }
        );
        $form->setTitle("List unused GiftCode");
        foreach (array_keys($this->plugin->code) as $code)
        {
            $form->addLabel($code . " [" .$this->plugin->code[$code] ."]");
        }
        $form->sendToPlayer($player);
    }
}
