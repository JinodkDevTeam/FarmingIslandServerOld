<?php

namespace NgLamVN\GameHandle\AchivementSystem\form;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\GameHandle\AchivementSystem\AchivementManager;
use NgLamVN\GameHandle\CoinSystem\CoinSystem;
use pocketmine\Player;

class AchivementForm
{
    public function __construct(Player $player)
    {
        $this->mainForm($player);
    }

    public function getManager(): AchivementManager
    {
        return AchivementManager::getInstance();
    }

    public function mainForm (Player $player)
    {
        if (!$this->getManager()->isHaveData($player))
        {
            $this->getManager()->registerBaseData($player);
        }
        $form = new SimpleForm(function (Player $player, $data)
        {
            var_dump($data);
            if (!isset($data))
            {
                return;
            }
            $achivement = $this->getManager()->getAchivement($data);
            $level = $this->getManager()->getPlayerData($player->getName())->getLevel($data);
            $maxlevel = $achivement->getMaxLevel();
            if ($maxlevel < $level)
            {
                return;
            }
            $p_count = $this->getManager()->getPlayerData($player->getName())->getCount($data);
            $a_count = $achivement->getCount($level);
            if ($p_count < $a_count)
            {
                $this->infoForm($player, $data);
                return;
            }
            $reward = $this->getManager()->getAchivement($data)->getReward($level);
            $this->getManager()->getPlayerData($player->getName())->setLevel($data, $level + 1);
            $this->getManager()->getPlayerData($player->getName())->setCount($data, 0);
            CoinSystem::getInstance()->addCoin($player, $reward);
            $player->sendMessage("You have earned " . $reward . " coin");
        });
        $form->setTitle("Achievement");
        foreach ($this->getManager()->getAllAchivement() as $achivement)
        {
            $a_name = $achivement->getName();
            $a_id = $achivement->getId();
            $level = $this->getManager()->getPlayerData($player->getName())->getLevel($a_id);
            $maxlevel = $achivement->getMaxLevel();
            $p_count = $this->getManager()->getPlayerData($player->getName())->getCount($a_id);
            $des = $achivement->getDescription();
            if ($maxlevel < $level)
            {
                $number = $maxlevel;
            }
            else
            {
                $number = $level;
            }
            $a_count = $achivement->getCount($number);
            switch ($number)
            {
                case 1:
                    $n = "I";
                    break;
                case 2:
                    $n = "II";
                    break;
                case 3:
                    $n = "III";
                    break;
                case 4:
                    $n = "IV";
                    break;
                case 5:
                    $n = "V";
                    break;
                default:
                    $n = $a_id;
                    break;
            }
            if ($maxlevel >= $level)
            {
                if ($p_count < $a_count)
                {
                    $name = $a_name . " " . $n . "\n" . $p_count . "/" . $a_count;
                } else
                    {
                    $name = $a_name . " " . $n . "\n" . "TAP FOR REWARD";
                }
            }
            else
            {
                $name = $a_name . " " . $n . "\n" . "COMPLETE !";
            }
            $form->addButton($name);
        }
        $player->sendForm($form);
    }
    public function infoForm(Player $player, $id)
    {
        $achivement = $this->getManager()->getAchivement($id);
        $form = new CustomForm(function (Player $player, $data)
        {
            return;
        });
        $form->setTitle($achivement->getName());
        $form->addLabel("Info: " . $achivement->getDescription());
        $player->sendForm($form);
    }
}
