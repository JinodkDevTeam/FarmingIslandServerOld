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
        $form->setTitle("UPDATES");
        $form->addLabel("Add Items when claim Island !");
        $form->addLabel("RedStone now available to use.");
        $form->addLabel("Sell and shop now available to use :)");
        $form->addLabel("Vui lòng liên hệ admin nếu thấy giá không phù hợp hoặc thêm item bla bla :3");
        $form->addLabel("Server Version: 0.1.3-beta");
        $player->sendForm($form);
    }
}
