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
        $form->addLabel("- Update base mcbe version to 1.16.200");
        $form->addLabel("- AfkArea: Get money by afk in this world (500xu per 30mins)");
        $form->addLabel("- Menu: UI mode support");
        $form->addLabel("- Now use Teleport menu to go warp to another island");
        $form->addLabel("- Add some options to Island Manager");
        $form->addLabel("- Add some features for admin");
        $form->addLabel("Official wiki: bit.ly/fi-wiki");
        $form->addLabel("Vote for server: bit.ly/fi-vote");
        $form->addLabel("Official Facebook group: bit.ly/jinodkgroupfb");
        $form->addLabel("Server Version: 0.1.11-beta");
        $player->sendForm($form);
    }
}
