<?php
declare(strict_types = 1);

/**
 * @name BasicAddon
 * @version 1.1.0
 * @main    JackMD\ScoreHud\Addons\BasicAddon
 */

namespace JackMD\ScoreHud\Addons
{

	use JackMD\ScoreHud\addon\AddonBase;
	use pocketmine\Player;

	class BasicAddon extends AddonBase
    {

        /**
         * @param Player $player
         * @return array
         */
        public function getProcessedTags(Player $player): array
        {
            return [
                "{name}" => $player->getName(),
                "{online}" => count($player->getServer()->getOnlinePlayers()),
                "{max_online}" => $player->getServer()->getMaxPlayers(),
                "{item_name}" => $player->getInventory()->getItemInHand()->getName(),
                "{item_id}" => $player->getInventory()->getItemInHand()->getId(),
                "{item_meta}" => $player->getInventory()->getItemInHand()->getDamage(),
                "{item_count}" => $player->getInventory()->getItemInHand()->getCount(),
                "{x}" => intval($player->getX()),
                "{y}" => intval($player->getY()),
                "{z}" => intval($player->getZ()),
                "{load}" => $player->getServer()->getTickUsage(),
                "{tps}" => $player->getServer()->getTicksPerSecond(),
                "{level_name}" => $player->getLevel()->getName(),
                "{level_folder_name}" => $player->getLevel()->getFolderName(),
                "{ip}" => $player->getAddress(),
                "{ping}" => $this->getPing($player),
                "{time}" => date($this->getScoreHud()->getConfig()->get("time-format")),
                "{date}" => date($this->getScoreHud()->getConfig()->get("date-format")),
                "{world_player_count}" => count($player->getLevel()->getPlayers()),
                "{line}" => PHP_EOL
            ];
        }

        public function getPing(Player $player)
        {
            $ping = $player->getPing();

            if ($ping < 100)
            {
                return "§a" . $ping. "§f ms";
            }
            if ($ping < 250)
            {
                return "§e" . $ping. "§f ms";
            }
            if ($ping >= 250)
            {
                return "§c" . $ping. "§f ms";
            }
        }
	}
}
