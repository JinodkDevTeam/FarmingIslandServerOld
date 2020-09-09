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
        $form->addLabel("-Null");
        $form->addLabel("Fixes:");
        $form->addLabel("Fix lỗi cây vẫn tiếp tục mọc mặc dù đã bị đập trước đó.");
        $form->addLabel("**Vui lòng liên hệ admin nếu thấy giá không phù hợp hoặc thêm item bla bla :3");
        $form->addLabel("Server Version: 0.1.5-beta");
        $player->sendForm($form);
    }
}
