<?php

namespace NgLamVN\NapThe;

use onebone\pointapi\PointAPI;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class NapThe extends PluginBase
{
    /** @var self */
    public static $instance;
    /** @var $cfg */
    public $cfg;
    /** @var $data */
    public $data;

    //TODO: Phần này vui lòng lấy ở napthengay.com
    public const MERCHANT_ID = "4889";
    public const API_EMAIL = "nglamvn2911@gmail.com";
    public const SECURE_CODE = "3705ffe2089a6933088e9ac24df0852c";

    //WARNING: DONT CHANGE THIS !
    public const API_URL = "http://api.napthengay.com/v2/";
    public const ALGO_MODE = "hmac";

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register("napthe", new CMD($this));
        $this->cfg = new Config($this->getDataFolder() . "data.yml");
        $this->data = $this->cfg->getAll();
        $this->cfg->setAll($this->data);
        $this->cfg->save();
    }

    public function onDisable()
    {
        $this->cfg->setAll($this->data);
        $this->cfg->save();
    }

    public static function getInstance(): NapThe
    {
        return self::$instance;
    }

    public function getCardID(int $type): string
    {
        switch ($type)
        {
            case 1:
                return "Viettel";
                break;
            case 2:
                return "Mobifone";
                break;
            case 3:
                return "Vinaphone";
                break;
            case 4:
                return "Zing";
                break;
            case 5:
                return "Gate";
                break;
            case 6:
                return "Vcoin";
                break;
        }
    }

    public function ToPoint ($amount): int
    {
        //TODO : KM = Khuyến mại. Vd X2 thì $km = $point * 2;
        switch ($amount)
        {
            case 10000:
                $point = 10;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 20000:
                $point = 20;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 50000:
                $point = 50;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 100000:
                $point = 100;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 200000:
                $point = 200;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 500000:
                $point = 500;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
            case 1000000:
                $point = 1000;
                $km = $point * 0;
                $nhan = $km + $point;
                break;
        }
        return $nhan;
    }

    public function XuLy(Player $player, int $card_id, string $card_value, string $pin_field, string $seri_field)
    {
        $trans_id = time();

        $this->data[$player->getName()][$trans_id] = [
            "card_id" => $this->getCardID($card_id),
            "card_value" => $card_value,
            "pin_field" => $pin_field,
            "seri_field" => $seri_field,
            "status" => "Đang chờ xử lý ..."
        ];
        $arrayPost = [
            "merchant_id" => intval(self::MERCHANT_ID),
            "api_email" => trim(self::API_EMAIL),
            "trans_id" => trim($trans_id),
            "card_id" => trim($card_id),
            "card_value" => intval($card_value),
            "pin_field" => trim($pin_field),
            "seri_field" => trim($seri_field),
            "algo_mode" => self::ALGO_MODE
        ];
        $data_sign = hash_hmac("SHA1", implode("", $arrayPost), self::SECURE_CODE);
        $arrayPost["data_sign"] = $data_sign;
        $curl = curl_init(self::API_URL);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => http_build_query($arrayPost)
        ]);
        $data = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($data, true);
        $time = time();

        if ($status !== 200)
        {
            $player->sendMessage("Lỗi dữ liệu, vui lòng thông báo cho admin.");
            return;
        }

        switch ($result["code"])
        {
            case 100:
                $status = "Nạp thành công";
                $nhan = $this->ToPoint($result["amount"]);
                PointAPI::getInstance()->addPoint($player, $nhan);
                $player->sendMessage("Nap The Thanh Cong !");
                break;
            case 101:
                $status = "Dữ liệu DataSign không đúng";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 102:
                $status = "Lỗi nhà mạng đang bảo trì hoặc gặp sự cố.";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 103:
            case 104:
                $status = "Lỗi tài khoản";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 105:
                $status = "Hệ thống quá tải";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 106:
                $status = "Mệnh giá thẻ cào không được hổ trợ";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 107:
            case 108:
                $status = "Thông tin thẻ không chính xác hoặc hệ thống gặp sự cố";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 109:
                $status = "Nạp thẻ thành công nhưng sai mệnh giá thẻ";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 110:
                $status = "Hệ thống quá tải";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 111:
                $status = "Sai định dạng thẻ cào";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 112:
            case 113:
                $status = "Nạp thẻ sai quá nhiều lần hoặc hệ thống quá tải";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 114:
                $status = "Thẻ cào đã được sử dụng";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
            case 0:
                $status = "Lỗi dữ liệu";
                $player->sendMessage("Nạp thẻ không thành công, kiểm tra lịch sử nạp thẻ để biết thêm chi tiết.");
                break;
        }
        $this->data[$player->getName()][$trans_id] = [
            "card_id" => $this->getCardID($card_id),
            "card_value" => $card_value,
            "pin_field" => $pin_field,
            "seri_field" => $seri_field,
            "status" => $status
        ];
        //TODO: SAVE DATA
        $this->cfg->setAll($this->data);
        $this->cfg->save();

        var_dump($card_id);
        var_dump($card_value);
        var_dump($pin_field);
        var_dump($seri_field);
        var_dump($status);
    }
}