<?php

declare(strict_types=1);

namespace vixikhd\snow;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\LittleEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\BinaryStream;

/**
 * Class Snow
 * @package vixikhd\snow
 */
class Snow extends PluginBase implements Listener {

    public const LOCAL_DEFINITIONS_PATH = \pocketmine\RESOURCE_PATH . 'vanilla/biome_definitions.nbt';
    public const GITHUB_DEFINITIONS_PATH = "https://github.com/pmmp/BedrockData/raw/cc132c80dd9d76a44e4b0a360e85e8e28bba8956/biome_definitions.nbt";

    // < 1.13
    public const PROTOCOL_1_13 = 388;
    public const HARDCODED_NBT_BLOB = "CgAKDWJhbWJvb19qdW5nbGUFCGRvd25mYWxsZmZmPwULdGVtcGVyYXR1cmUzM3M/AAoTYmFtYm9vX2p1bmdsZV9oaWxscwUIZG93bmZhbGxmZmY/BQt0ZW1wZXJhdHVyZTMzcz8ACgViZWFjaAUIZG93bmZhbGzNzMw+BQt0ZW1wZXJhdHVyZc3MTD8ACgxiaXJjaF9mb3Jlc3QFCGRvd25mYWxsmpkZPwULdGVtcGVyYXR1cmWamRk/AAoSYmlyY2hfZm9yZXN0X2hpbGxzBQhkb3duZmFsbJqZGT8FC3RlbXBlcmF0dXJlmpkZPwAKGmJpcmNoX2ZvcmVzdF9oaWxsc19tdXRhdGVkBQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlMzMzPwAKFGJpcmNoX2ZvcmVzdF9tdXRhdGVkBQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlMzMzPwAKCmNvbGRfYmVhY2gFCGRvd25mYWxsmpmZPgULdGVtcGVyYXR1cmXNzEw9AAoKY29sZF9vY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAD8ACgpjb2xkX3RhaWdhBQhkb3duZmFsbM3MzD4FC3RlbXBlcmF0dXJlAAAAvwAKEGNvbGRfdGFpZ2FfaGlsbHMFCGRvd25mYWxszczMPgULdGVtcGVyYXR1cmUAAAC/AAoSY29sZF90YWlnYV9tdXRhdGVkBQhkb3duZmFsbM3MzD4FC3RlbXBlcmF0dXJlAAAAvwAKD2RlZXBfY29sZF9vY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAD8AChFkZWVwX2Zyb3plbl9vY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAAAAChNkZWVwX2x1a2V3YXJtX29jZWFuBQhkb3duZmFsbAAAAD8FC3RlbXBlcmF0dXJlAAAAPwAKCmRlZXBfb2NlYW4FCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmUAAAA/AAoPZGVlcF93YXJtX29jZWFuBQhkb3duZmFsbAAAAD8FC3RlbXBlcmF0dXJlAAAAPwAKBmRlc2VydAUIZG93bmZhbGwAAAAABQt0ZW1wZXJhdHVyZQAAAEAACgxkZXNlcnRfaGlsbHMFCGRvd25mYWxsAAAAAAULdGVtcGVyYXR1cmUAAABAAAoOZGVzZXJ0X211dGF0ZWQFCGRvd25mYWxsAAAAAAULdGVtcGVyYXR1cmUAAABAAAoNZXh0cmVtZV9oaWxscwUIZG93bmZhbGyamZk+BQt0ZW1wZXJhdHVyZc3MTD4AChJleHRyZW1lX2hpbGxzX2VkZ2UFCGRvd25mYWxsmpmZPgULdGVtcGVyYXR1cmXNzEw+AAoVZXh0cmVtZV9oaWxsc19tdXRhdGVkBQhkb3duZmFsbJqZmT4FC3RlbXBlcmF0dXJlzcxMPgAKGGV4dHJlbWVfaGlsbHNfcGx1c190cmVlcwUIZG93bmZhbGyamZk+BQt0ZW1wZXJhdHVyZc3MTD4ACiBleHRyZW1lX2hpbGxzX3BsdXNfdHJlZXNfbXV0YXRlZAUIZG93bmZhbGyamZk+BQt0ZW1wZXJhdHVyZc3MTD4ACg1mbG93ZXJfZm9yZXN0BQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlMzMzPwAKBmZvcmVzdAUIZG93bmZhbGzNzEw/BQt0ZW1wZXJhdHVyZTMzMz8ACgxmb3Jlc3RfaGlsbHMFCGRvd25mYWxszcxMPwULdGVtcGVyYXR1cmUzMzM/AAoMZnJvemVuX29jZWFuBQhkb3duZmFsbAAAAD8FC3RlbXBlcmF0dXJlAAAAAAAKDGZyb3plbl9yaXZlcgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAAAACgRoZWxsBQhkb3duZmFsbAAAAAAFC3RlbXBlcmF0dXJlAAAAQAAKDWljZV9tb3VudGFpbnMFCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmUAAAAAAAoKaWNlX3BsYWlucwUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAAAAChFpY2VfcGxhaW5zX3NwaWtlcwUIZG93bmZhbGwAAIA/BQt0ZW1wZXJhdHVyZQAAAAAACgZqdW5nbGUFCGRvd25mYWxsZmZmPwULdGVtcGVyYXR1cmUzM3M/AAoLanVuZ2xlX2VkZ2UFCGRvd25mYWxszcxMPwULdGVtcGVyYXR1cmUzM3M/AAoTanVuZ2xlX2VkZ2VfbXV0YXRlZAUIZG93bmZhbGzNzEw/BQt0ZW1wZXJhdHVyZTMzcz8ACgxqdW5nbGVfaGlsbHMFCGRvd25mYWxsZmZmPwULdGVtcGVyYXR1cmUzM3M/AAoOanVuZ2xlX211dGF0ZWQFCGRvd25mYWxsZmZmPwULdGVtcGVyYXR1cmUzM3M/AAoTbGVnYWN5X2Zyb3plbl9vY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAAAACg5sdWtld2FybV9vY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAD8ACgptZWdhX3RhaWdhBQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlmpmZPgAKEG1lZ2FfdGFpZ2FfaGlsbHMFCGRvd25mYWxszcxMPwULdGVtcGVyYXR1cmWamZk+AAoEbWVzYQUIZG93bmZhbGwAAAAABQt0ZW1wZXJhdHVyZQAAAEAACgptZXNhX2JyeWNlBQhkb3duZmFsbAAAAAAFC3RlbXBlcmF0dXJlAAAAQAAKDG1lc2FfcGxhdGVhdQUIZG93bmZhbGwAAAAABQt0ZW1wZXJhdHVyZQAAAEAAChRtZXNhX3BsYXRlYXVfbXV0YXRlZAUIZG93bmZhbGwAAAAABQt0ZW1wZXJhdHVyZQAAAEAAChJtZXNhX3BsYXRlYXVfc3RvbmUFCGRvd25mYWxsAAAAAAULdGVtcGVyYXR1cmUAAABAAAoabWVzYV9wbGF0ZWF1X3N0b25lX211dGF0ZWQFCGRvd25mYWxsAAAAAAULdGVtcGVyYXR1cmUAAABAAAoPbXVzaHJvb21faXNsYW5kBQhkb3duZmFsbAAAgD8FC3RlbXBlcmF0dXJlZmZmPwAKFW11c2hyb29tX2lzbGFuZF9zaG9yZQUIZG93bmZhbGwAAIA/BQt0ZW1wZXJhdHVyZWZmZj8ACgVvY2VhbgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAD8ACgZwbGFpbnMFCGRvd25mYWxszczMPgULdGVtcGVyYXR1cmXNzEw/AAobcmVkd29vZF90YWlnYV9oaWxsc19tdXRhdGVkBQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlmpmZPgAKFXJlZHdvb2RfdGFpZ2FfbXV0YXRlZAUIZG93bmZhbGzNzEw/BQt0ZW1wZXJhdHVyZQAAgD4ACgVyaXZlcgUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZQAAAD8ACg1yb29mZWRfZm9yZXN0BQhkb3duZmFsbM3MTD8FC3RlbXBlcmF0dXJlMzMzPwAKFXJvb2ZlZF9mb3Jlc3RfbXV0YXRlZAUIZG93bmZhbGzNzEw/BQt0ZW1wZXJhdHVyZTMzMz8ACgdzYXZhbm5hBQhkb3duZmFsbAAAAAAFC3RlbXBlcmF0dXJlmpmZPwAKD3NhdmFubmFfbXV0YXRlZAUIZG93bmZhbGwAAAA/BQt0ZW1wZXJhdHVyZc3MjD8ACg9zYXZhbm5hX3BsYXRlYXUFCGRvd25mYWxsAAAAAAULdGVtcGVyYXR1cmUAAIA/AAoXc2F2YW5uYV9wbGF0ZWF1X211dGF0ZWQFCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmUAAIA/AAoLc3RvbmVfYmVhY2gFCGRvd25mYWxsmpmZPgULdGVtcGVyYXR1cmXNzEw+AAoQc3VuZmxvd2VyX3BsYWlucwUIZG93bmZhbGzNzMw+BQt0ZW1wZXJhdHVyZc3MTD8ACglzd2FtcGxhbmQFCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmXNzEw/AAoRc3dhbXBsYW5kX211dGF0ZWQFCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmXNzEw/AAoFdGFpZ2EFCGRvd25mYWxszcxMPwULdGVtcGVyYXR1cmUAAIA+AAoLdGFpZ2FfaGlsbHMFCGRvd25mYWxszcxMPwULdGVtcGVyYXR1cmUAAIA+AAoNdGFpZ2FfbXV0YXRlZAUIZG93bmZhbGzNzEw/BQt0ZW1wZXJhdHVyZQAAgD4ACgd0aGVfZW5kBQhkb3duZmFsbAAAAD8FC3RlbXBlcmF0dXJlAAAAPwAKCndhcm1fb2NlYW4FCGRvd25mYWxsAAAAPwULdGVtcGVyYXR1cmUAAAA/AAA=";

    public const MIN_WINTER_TEMPERATURE = -5;
    public const MAX_WINTER_TEMPERATURE = -1;

    /** @var string $dataForPacket */
    public static $dataForPacket;

    public $task;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        /** @var NetworkLittleEndianNBTStream $stream */
        $stream = new NetworkLittleEndianNBTStream();
        /** @var CompoundTag $compound */
        $compound = null;

        if(!file_exists(self::LOCAL_DEFINITIONS_PATH)) {
            if (ProtocolInfo::CURRENT_PROTOCOL < self::PROTOCOL_1_13) {
                $compound = $stream->read(base64_decode(self::HARDCODED_NBT_BLOB));
            } else {
                $compound = $stream->read(file_get_contents(self::GITHUB_DEFINITIONS_PATH, false, stream_context_create([
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ]
                    ]
                )));
            }
        }
        else {
            $compound = $stream->read(file_get_contents(self::LOCAL_DEFINITIONS_PATH));
        }

        $winterData = new CompoundTag();

        foreach ($compound->getValue() as $biomeName => $biomeCompound) {
            $biomeData = new CompoundTag($biomeName);
            foreach ($biomeCompound as $index => $value) {
                if($index == "temperature") {
                    $value = new FloatTag("temperature", (float)(rand(self::MIN_WINTER_TEMPERATURE, self::MAX_WINTER_TEMPERATURE) / 10));
                }

                $biomeData->offsetSet($index, $value);
            }

            $winterData->offsetSet($biomeName, $biomeData);
        }

        $buffer = (new NetworkLittleEndianNBTStream())->write($winterData);

        self::$dataForPacket = ProtocolInfo::CURRENT_PROTOCOL < self::PROTOCOL_1_13 ? base64_encode($buffer) : $buffer;

        $this->task = new SnowTask($this);
        $this->getScheduler()->scheduleRepeatingTask($this->task, 18000); //Per 15 mins
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if ($this->task->isRain())
        {
            $pk = new LevelEventPacket();
            $pk->evid = LevelEventPacket::EVENT_START_RAIN;
            $pk->data = 2000;

            $player->dataPacket($pk);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() !== "weatherclear") return true;
        if (!$sender->isOp())
        {
            return true;
        }
        $players = $this->getServer()->getOnlinePlayers();
        foreach ($players as $player)
        {
            $pk = new LevelEventPacket();
            $pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
            $pk->data = 1;

            $player->dataPacket($pk);
        }
        return true;
    }

    /**
     * @param DataPacketSendEvent $event
     */
    public function onSend(DataPacketSendEvent $event) {
        $packet = $event->getPacket();
        if(!($packet instanceof BiomeDefinitionListPacket) && ($packet instanceof \pocketmine\network\mcpe\protocol\BiomeDefinitionListPacket)) {
            $event->setCancelled();
            $event->getPlayer()->dataPacket(new BiomeDefinitionListPacket());
        }
    }
}