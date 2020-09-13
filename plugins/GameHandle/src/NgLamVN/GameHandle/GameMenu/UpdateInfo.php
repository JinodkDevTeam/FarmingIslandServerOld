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
        $form->addLabel("-Vote system");
        $form->addLabel("Fixes:");
        $form->addLabel("Fix lỗi không dùng được lệnh sell");
        $form->addLabel("Fix lỗi câu cá đôi khi bị văng game");
        $form->addLabel("Fix lỗi dùng cần câu mở rương người khác.
Fix lỗi drop hạt giống khi đất bị phá.
Farmland giờ sẽ không bị chuyển thành dirt khi nhảy lên.
        $form->addLabel("**Vui lòng liên hệ admin nếu thấy giá không phù hợp hoặc thêm item bla bla :3");
        $form->addLabel("LƯU Ý:");
        $form->addLabel("• Vì server đang ở BETA (Thử nghiệm), nên chắc chắn rồi, nó không phải là 1 server hoàn thiện, do đó còn tồn tại rất nhiều lỗi, 1 số thứ không hoạt động.
    • Bạn có thể mất dữ liệu về inventory, mất items, mất tiền, thậm chí mất cả đảo, do đó nên cân nhắc khi chơi ở phiên bản beta. (Bọn mình cũng sẽ hạn chế việc này hết mức có thể)");
        $form->addLabel("Server Version: 0.1.5-beta");
        $player->sendForm($form);
    }
}
