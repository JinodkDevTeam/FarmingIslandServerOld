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
        $form->addLabel("- Update Menu :)");
        $form->addLabel("Achivement [Comming soon !]");
        $form->addLabel("VipSystem");
        $form->addLabel("SellSystem [Comming Soon !]");
        $form->addLabel("CoinSystem");
        $form->addLabel("Update gamemode commands");
        $form->addLabel("/gms, /gmc, /gma, /gm0, /gm1, /gm2, gm3");
        $form->addLabel("* Reduce lag !");
        $form->addLabel("Update FishingSystem (More Items)");
        $form->addLabel("Update Wiki [Will available soon !]");
        $form->addLabel("PVP và KeepInv đc thêm vào ở world island");
        $form->addLabel("Nạp thẻ [Đang bảo trì]");
        $form->addLabel("------FIXES------");
        $form->addLabel("Fix lỗi vẫn pay được coin dù không đủ tiền.");
        $form->addLabel("Fix lỗi khi chết không về lại đảo.");
        $form->addLabel("Fix lỗi out server khi pay coin với amount không phải là số.");
        $form->addLabel("Fix lỗi bug coin khi pay với số coin âm");
        $form->addLabel("Fix lỗi số coin không thể vượt quá 2^31");
        $form->addLabel("Fix lỗi out server khi pay coin với số thập phân.");
        $form->addLabel("Server Version: 0.1.2-beta");
        $player->sendForm($form);
    }
}
