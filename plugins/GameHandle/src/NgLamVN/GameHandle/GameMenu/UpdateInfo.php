<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class UpdateInfo
{
    public string $version;

    public function __construct(Player $player)
    {
        $this->version = Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle")->getDescription()->getVersion();
        $this->execute($player);
    }

    public function ArrayToString(array $array): string
    {
        $string = "";
        foreach ($array as $a)
        {
            if ($string == "") $string = $a;
            else $string = $string . "\n" . $a;
        }
        return $string;
    }

    public function execute(Player $player)
    {
        $form = new SimpleForm(function (Player $player, ?int $data)
        {
            if ($data == null) return;
            if ($data == 1) $this->TutorialForm($player);
        });
        $text = [
            "Updates:",
            "- Add Tutorial.",
            "Official wiki: bit.ly/fi-wiki",
            "Vote for server: bit.ly/fi-vote",
            "Official Facebook group: bit.ly/jinodkgroupfb",
            "Server Version: " . $this->version
        ];
        $form->setTitle("BREAKING NEWS");
        $form->setContent($this->ArrayToString($text));
        $form->addButton("OK");
        $form->addButton("Tutorial\nXem cách chơi.");

        $player->sendForm($form);
    }

    public function TutorialForm(Player $player)
    {
        $text = [
            "Chào mừng bạn đã đến với chế độ FarmingIsland, đây là một chế độ hoàn toàn khác biệt so với SkyBlock hay AcidIsland vì ở đây chúng ta sẽ không bị rớt xuống the void hay nước biển có độc nữa.",
            "Bạn sẽ khởi đầu với một cái cần câu, đất và hạt giống.",
            "Là một người chơi Minecraft bạn hi vọng bạn sẽ biết mình sẽ làm gì. Đúng rồi chúng ta sẽ trồng cây và sử dụng cần câu để câu cá",
            "Tuy nhiên, trong chế độ này ngoài việc bạn câu được cá, bạn có thể câu được các vật phẩm khác như đá, khoáng sản, nông sản, vân vân",
            "Bên cạnh đó tờ giấy bên phải hotbar của bạn là Island Menu chứa các tính năng cần thiết để bạn có thể chơi chế độ này:",
            "- IslandManager (Quản lý đảo): Chứa các chức năng liên quan đến việc quản lý đảo của bạn như thêm, xóa người giúp, thay đổi tên đảo, biome, ...",
            "- IslandInfo (Thông tin đảo): Chứa thông tin đảo bạn đang đứng (ID, Chủ đảo, danh sách người giúp, ...)",
            "- Teleport (Dịch chuyển): Bao gồm các chức năng dịch chuyển như:",
            "  + MyIsland: Dịch chuyển về đảo của bạn",
            "  + SpecialIsland: Danh sách những đảo đặc biệt, những đảo giành top1 trong các event xây dựng",
            "  + MineArea: Nơi bạn khai thác đá và khoáng sản",
            "  + AfkArea: Nơi bạn chỉ cần afk mỗi 20 phút là nhận 200xu (Không làm mà vẫn có ăn là có thật)",
            "  + Go to another Island (Di chuyển đến đảo khác): Tính năng giúp bạn đi đến đảo của bạn bè, miễn là bạn có ID đảo của họ",
            "- Shop: Nơi bạn có thể mua các vật phẩm",
            "- VipItemShop: Nơi bán những vật phẩm cao cấp, sách enchant, ... quang trọng là bạn có đủ tiền để mua nó ?",
            "- Sell All Inventory: Bán toàn bộ vật phẩm trong túi bạn",
            "- Coin: Chứa các chức năng liên quang đến coin (đưa coin, top coin, ...)",
            "- VIP: Chứa thông tin về các rank cao cấp.",
            "- RankColor(Chỉ xuất hiện nếu bạn có rank Member trở lên): Thay đổi màu rank của bạn.",
            "Ngoài ra còn một số lệnh cơ bản có thể giúp bạn trong khi chơi:",
            "- /autofeed: Luôn no",
            "- /autofix: tự động sửa đồ",
            "- /autopickup: tắt bật tự động nhặt vật phẩm khi đập block (mặc định là bật)",
            "- /pay <player>: đưa tiền người chơi khác",
            "- /topmoney: Top tiền",
            "Chúc các bạn chơi vui vẻ :)"
        ];

        $form = new SimpleForm(function (Player $player, ?int $data)
        {
            //NOTHING
        });
        $form->setTitle("Tutorial");
        $form->setContent($this->ArrayToString($text));
        $form->addButton("OK");

        $player->sendForm($form);
    }
}
