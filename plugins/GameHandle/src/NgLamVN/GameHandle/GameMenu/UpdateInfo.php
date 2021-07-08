<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\GameMenu;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class UpdateInfo
{
    public string $version;

    public function __construct(Player $player, string $mode = "")
    {
        $this->version = Server::getInstance()->getPluginManager()->getPlugin("FI-GameHandle")->getDescription()->getVersion();
        if ($mode == "") $this->execute($player);
        elseif ($mode == "tutorial")
        {
            $this->TutorialForm($player);
        }
        elseif ($mode == "event")
        {
            $this->eventForm($player);
        }
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
            if ($data == 2) $this->eventForm($player);
        });
        $text = [
            "§　Updates:",
            "- Add new VipItems",
            "Thông báo: GUI không còn được update trên Island Menu, các bạn vui lòng chuyển sang sử dụng UI trên Island Menu",
            "Official wiki: bit.ly/fi-wiki",
            "Vote for server: bit.ly/fi-vote",
            "Official Facebook group: bit.ly/jinodkgroupfb",
            "Server Version: " . $this->version
        ];
        $form->setTitle("§　BREAKING NEWS");
        $form->setContent($this->ArrayToString($text));
        $form->addButton("§　§lOK");
        $form->addButton("§　§lTutorial\nXem cách chơi.");
        $form->addButton("§　§l§eEVENT §0INFO");

        $player->sendForm($form);
    }

    public function TutorialForm(Player $player)
    {
        $text = [
            "Chào mừng bạn đã đến với chế độ FarmingIsland, đây là một chế độ hoàn toàn khác biệt so với SkyBlock hay AcidIsland vì ở đây chúng ta sẽ không bị rớt xuống the void hay phải tránh nước biển có độc nữa.",
            "Ở đây đảo sẽ tự tạo sẵng cho bạn nên bạn không cần phải lo về việc tạo đảo như thế nào, claim ra sao bla bla.",
            "Bạn sẽ khởi đầu với một cái cần câu, đất và hạt giống.",
            "Là một người chơi Minecraft hi vọng bạn sẽ biết mình phải làm gì. Đúng rồi chúng ta sẽ trồng cây và sử dụng cần câu để câu cá",
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
            "- /pay <player>: đưa tiền cho người chơi khác",
            "- /topmoney: Top tiền",
            "- /notp: Ngăn không cho người tác dịch chuyện đến bạn hay dịch chuyển bạn đi (*Trừ Admin*)",
            "Chúc các bạn chơi vui vẻ :)",
            "* FAQ *",
            "  Q: server có máy farm không ?",
            "  A: Tất nhiên là không, vì chả ai muốn một lối chơi đập đá là chính trong chế độ này",
            "",
            "  Q: Tại sao bán đồ được ít xu vậy ?",
            "  A: Bởi vì chẳng ai muốn mua đồ với hàng tá số 0 ở phía sau cả.",
            " *Tóm lại nếu bạn đã từng chơi server khác thì chế độ trong server bạn đang chơi là hoàn toàn khác biệt, do đó bạn đừng nên áp dụng cách chơi ở các server khác vào server này !"
        ];

        $form = new SimpleForm(function (Player $player, ?int $data)
        {
            //NOTHING
        });
        $form->setTitle("§　§lTutorial");
        $form->setContent($this->ArrayToString($text));
        $form->addButton("§　§lOK, LEST PLAY !");

        $player->sendForm($form);
    }

    public function eventForm(Player $player)
    {
        $text = [
            "§l§eEVENT §f#1:",
            "§r§f * TOP Vote (1/7 - 31/7/2021) *",
            " - TOP 1: 60.000xu + 5 Superium Essence",
            " - TOP 2: 50.000xu + 5 Intermedium Essence",
            " - TOP 3: 30.000xu + 5 Intermedium Essence",
            " - TOP 4: 30.000xu + 5 Prudentium Essence",
            " - TOP 5: 30.000xu",
            "§o**Giải thưởng chỉ có tác dụng nếu tổng số vote trong tháng của server > 150.**"
        ];

        $form = new SimpleForm(function (Player $player, ?int $data)
        {
            //NOTHING
        });
        $form->setTitle("§　§l§eEVENT §0INFO");
        $form->setContent($this->ArrayToString($text));
        $form->addButton("§　§lOK");

        $player->sendForm($form);
    }
}
