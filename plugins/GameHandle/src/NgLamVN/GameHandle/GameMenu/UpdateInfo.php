<?php

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\Player;
use pocketmine\Server;

class UpdateInfo
{
    public $version;

    public function __construct(Player $player)
    {
        $this->version = Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle")->getDescription()->getVersion();
        $this->execute($player);
    }

    public function execute(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data){ return; });
        $form->setTitle("BREAKING NEWS");
        $form->addLabel("Updates:");
        $form->addLabel("- Add toggle auto pickup item drop.");
        $form->addLabel("- Add Member rank.");
        $form->addLabel("Official wiki: bit.ly/fi-wiki");
        $form->addLabel("Vote for server: bit.ly/fi-vote");
        $form->addLabel("Official Facebook group: bit.ly/jinodkgroupfb");
        $form->addLabel("Server Version: " . $this->version);
        $player->sendForm($form);
    }
}
