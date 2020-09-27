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
        $form->addLabel("- Shop and loot items");
        $form->addLabel("Fixes:");
        $form->addLabel("- Fix block rương và một số block khác khi dùng island menu tap lên.");
        $form->addLabel("Fix lỗi người chơi với khoảng trống trong tên của họ có thể bug money và coin (server sẽ lưu lại dữ liệu để có thể đền bù khi có yêu cầu)");
        $form->addLabel("**Vui lòng liên hệ admin nếu thấy giá không phù hợp hoặc thêm item bla bla :3");
        $form->addLabel("Official wiki: bit.ly/fi-wiki");
        $form->addLabel("Vote for server: bit.ly/fi-vote");
        $form->addLabel("Official Facebook group: bit.ly/jinodkgroupfb");
        $form->addLabel("LƯU Ý:");
        $form->addLabel("• Vì server đang ở BETA (Thử nghiệm), nên chắc chắn rồi, nó không phải là 1 server hoàn thiện, do đó còn tồn tại rất nhiều lỗi, 1 số thứ không hoạt động.
    • Bạn có thể mất dữ liệu về inventory, mất items, mất tiền, thậm chí mất cả đảo, do đó nên cân nhắc khi chơi ở phiên bản beta. (Bọn mình cũng sẽ hạn chế việc này hết mức có thể)");
        $form->addLabel("Server Version: 0.1.6-beta");
        $player->sendForm($form);
    }
}
