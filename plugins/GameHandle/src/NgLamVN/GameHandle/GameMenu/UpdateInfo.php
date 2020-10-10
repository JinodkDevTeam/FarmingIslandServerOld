<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\Player;

class UpdateInfo
{
    public function __construct(Player $player)
    {
        $this->execute($player);
    }

    public function execute(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data){ return; });
        $form->setTitle("BREAKING NEWS");
        $form->addLabel("Updates:");
        $form->addLabel("- Achievement !!!");
        $form->addLabel("Official wiki: bit.ly/fi-wiki");
        $form->addLabel("Vote for server: bit.ly/fi-vote");
        $form->addLabel("Official Facebook group: bit.ly/jinodkgroupfb");
        $form->addLabel("Lưu ý: Achivement sau update này sẽ đc reset và khó hơn do đó hãy nhanh tay kiếm coin :)");
        $form->addLabel("Server Version: 0.1.8-beta");
        $player->sendForm($form);
    }
}
