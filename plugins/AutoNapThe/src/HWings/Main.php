<?php

namespace HWings;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\event\Listener;
use joejoe77777\FormAPI;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("§e===== §bPlugin Auto Nạp Tiền §e=====\n\n§bCoder by:§c Nguyễn Đông Quý\n\n§b=====§a Dành cho Server §cLand §eOF §bAnime §b=====\n\n§eIP: animemc.ddns.net PORT 19132");
        /*Plugin Coin khi nạp (tùy bạn)*/
        $this->pointapi = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
        $this->tn = $this->getServer()->getPluginManager()->getPlugin("TichNap");
        $trans_id = time();  //mã giao dịch do bạn gửi lên, Napthengay.com sẽ trả về
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() == "naptien") {
            if (!(isset($args[0]) || isset($args[1]) || isset($args[2]) || isset($args[3]))) {
                //Bảng help
                $sender->sendMessage("§b-=-=-=-=-=§f|§e HỆ THỐNG NẠP THẺ TỰ ĐỘNG §f|§b=-=-=-=-=-\n§bNhà mạng: <§eNhà Mạng: 1 - Viettel, 2 - Mobifone, 3 - Vinaphone§b>\n§bHướng dẫn nạp thẻ §a/napthe §b|Mã thẻ| |Seri| |Giá trị: 10000, 20000, 50000, 100000, 200000, 500000, 1000000| |Nhà mạng|\n§bVí dụ§e: Bạn muốn nạp thẻ Viettel 20K bạn ghi /naptien 9301738291038 04928174829 20000 1\n§l§c Thực hiện sai dẫn đến mất thẻ, OP sẽ không chịu trách nhiệm!\n§b-=-=-=-=-=§f|§e HỆ THỐNG NẠP THẺ TỰ ĐỘNG §f|§b=-=-=-=-=-
\n§b-=-=-=-=-=§f|§e BẢNG GIÁ §f|§b=-=-=-=-=-\n§c•§b 10.000đ = §e10 LCoin\n§c•§b 20.000đ = §e20 LCoin\n§c•§b 50.000đ = §e50 LCoin\n§c•§b 100.000đ = §e100 LCoin\n§c•§b 200.000đ = §e200 Lcoin\n§c•§b 500.000đ = §e500 LCoin\n§c•§b 1.000.000đ = §e1.000 LCoin\n§l§c• Máy Chủ hiện đang khuyến mãi 10% và 100% cho các mệnh giá 100k trở lên§r\n§b-=-=-=-=-=§f|§e BẢNG GIÁ §f|§b=-=-=-=-=-");
                return true;
            }
            if (!(is_numeric($args[0]) || is_numeric($args[1]) || is_numeric($args[2]) || is_numeric($args[3]))) {
                //cảnh báo khi seri + pin + giá trị k phải là số
                $sender->sendMessage("§d•§e Số Serial/Số PIN/Giá Trị/Loại Mạng mà bạn vừa nhập không phải là số, hãy thử lại!");
                return true;
            }
            if (!($args[2] == 10000 || $args[2] == 20000 || $args[2] == 50000 || $args[2] == 100000 || $args[2] == 200000 || $args[2] == 500000 || $args[2] == 1000000)) {
                $sender->sendMessage("§d•§e Giá Trị mà bạn vừa nhập hiện Server chưa hỗ trợ, hãy thử lại!");
                return true;
            }
            if ($args[3] > 3 || $args[3] < 1) {
                $sender->sendMessage("§d•§b Loại Nhà Mạng mà bạn vừa nhập Server chưa hỗ trợ, hãy thử lại!");
                return true;
            }
            switch ($args[3]) {
                case "1":
                    $ten = "Viettel";
                    $mang = 1;
                    break;
                case "2":
                    $ten = "Mobifone";
                    $mang = 2;
                    break;
                case "3":
                    $ten = "Vinaphone";
                    $mang = 3;
            }
            //ID khi đăng ký trên napthengay.com
            $merchant_id = "4142";
            //email đăng ký trên đó
            $api_email = "yeuoanhnhieu.99@gmail.com";
//key khi đăng ký
            $secure_code = "9677d86a3490a95a8a94296c07de08e0";
            $api_url = "http://api.napthengay.com/v2/";
            $trans_id = time();
            $seri = $args[1];
            $sopin = $args[0];
            $card_value = $args[2];
            $mang = $mang;
            $user = $sender->getName();
            $arrayPost = array(
                "merchant_id" => intval($merchant_id),
                "api_email" => trim($api_email),
                "trans_id" => trim($trans_id),
                "card_id" => trim($mang),
                "card_value" => intval($card_value),
                "pin_field" => trim($sopin),
                "seri_field" => trim($seri),
                "algo_mode" => "hmac"
            );
            $data_sign = hash_hmac("SHA1", implode("", $arrayPost), $secure_code);

            $arrayPost["data_sign"] = $data_sign;

            $curl = curl_init($api_url);

            curl_setopt_array($curl, array(
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => http_build_query($arrayPost)
            ));

            $data = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            $result = json_decode($data, true);

            $time = time();

            if ($status == 200) {
                $amount = $result["amount"];
                switch ($amount) {
                    //cái zcoin là loại coin bạb đã khai báo ở đầu
                    case 10000:
                        $point = 10;
                        $km = $point * 0.1;
                        $nhan = $km + $point;
                        break;
                    case 20000:
                        $point = 20;
                        $km = $point * 0.1;
                        $nhan = $km + $point;
                        break;
                    case 50000:
                        $point = 50;
                        $km = $point * 0.1;
                        $nhan = $km + $point;
                        break;
                    case 100000:
                        $point = 100;
                        $km = $point * 1;
                        $nhan = $km + $point;
                        break;
                    case 200000:
                        $point = 200;
                        $km = $point * 1;
                        $nhan = $km + $point;
                        break;
                    case 500000:
                        $point = 500;
                        $km = $point * 1;
                        $nhan = $km + $point;
                        break;
                    case 1000000:
                        $point = 1000;
                        $km = $point * 1;
                        $nhan = $km + $point;
                        break;
                    //nếu muốn thêm % khuyến mãi như này:
                    /*
                    $zcoin = 10;
                    $nhan = $zcoin*1.5 hoặc 2
                    1.5 là kmãi 50%
                    2 là kmãi 100%
                    10k = 20
            20 = 40
            50 = 100
            100 = 200
            200 = 400
            500 = 1000
                    */


                }

                if ($result["code"] == 100) {
                    /*$dbhost="localhost";
                    $dbuser="CDN";
                    $dbpass="ahjhj123";
                    $dbname="DonateData";
                    $db = mysql_connect($dbhost,$dbuser,$dbpass) or die("cant connect db");
                    mysql_select_db($dbname,$db) or die("cant select db");
                    mysql_query("UPDATE users SET coins = coins + ".$zcoin." WHERE login =".$user.";");*/
                    // Xu ly thong tin tai day
                    $file = "carddung.log";
                    $fh = fopen($file, "a") or die("cant open file");
                    fwrite($fh, "Tai khoan: " . $user . ", Loai the: " . $ten . ", Menh gia: " . $amount . ", Thoi gian: " . $time);
                    fwrite($fh, "\r\n");
                    fclose($fh);
                    //lời nhắn khi nạp hành công
                    $this->pointapi->addPoint($sender->getName(), $nhan);
                    $this->tn->addTichNap($sender->getName(), $args[2]);
                    $sender->sendMessage("§c•§b Chúc mừng bạn đã nạp thành công§e " . $point . " Lcoin§b, khuyến mãi §e100%§b thành §e" . $nhan . " Lcoin");
                    $sender->sendMessage("§c•§b Tổng số tiền bạn đã nạp: " . $this->tn->viewTichNap($sender->getName()) . " VNĐ");

                } else {
                    $sender->sendMessage("§c•§b Nạp tiền không thành công, lỗi§e: " . $result["code"] . " ");
                    $error = $result["msg"];
                    $file = "cardsai.log";
                    $fh = fopen($file, "a") or die("cant open file");
                    fwrite($fh, "Tai khoan: " . $user . ", Ma the: " . $sopin . ", Seri: " . $seri . ", Noi dung loi: " . $error . ", Thoi gian: " . $time);
                    fwrite($fh, "\r\n");
                    fclose($fh);
                    //Gửi lỗi (từ napthengay.com trả về)
                    $sender->sendMessage("§c•§b Lỗi§e: " . $error);
                }
            } else {
                //thông báo khi id - key đăng ký trên napthengay.com không khớp
                $sender->sendMessage("§c•§e Dữ liệu không khớp!");
                /*   KẾT
                PLUGIN NÀY ĐƯỢC TẠO RA NHẰM MỤC ĐÍCH DẰN MẶT AI ĐÓ ĐÓ :), THÍCH ĐỘC QUYỀN THÌ T CHO ĐỘC QUYỀN, CÁC BẠN CÓ THỂ SHARE RỘNG RÃI NHƯNG LÀM ƠN HÃY NHỚ ĐẾN CÁI NGUỒN LÀ NGUYỄN ĐÔNG QUÝ HIHI
                SV CỦA T :
                :<
                :<
                THÂN ÁI VÀ DÍ DÁI VÀO MẶT ANH
                */
            }
        }
        return true;
    }
}