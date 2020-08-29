<?php
declare(strict_types = 1);

/**
 * @name PlayerLevelApiAddon
 * @version 0.0.1
 * @main JackMD\ScoreHud\Addons\PlayerLevelApiAddon
 * @depend LpkPlayerLevelAPI
 */
namespace JackMD\ScoreHud\Addons
{

	use JackMD\ScoreHud\addon\AddonBase;
	use pocketmine\Player;
	use LamPocketVN\PlayerLevelAPI\PlayerLevelAPI;

	class PlayerLevelApiAddon extends AddonBase{

		/** @var plevel */
		private $plevel;

		public function onEnable(): void
		{
			$this->plevel = $this->getServer()->getPluginManager()->getPlugin("LpkPlayerLevelAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{level}"           => $this->getLevel($player),
				"{maxlevel}" 		=> $this->getMaxLevel($player),
				"{xp}"       		=> $this->getXp($player),
				"{maxxp}"           => $this->getMaxXp($player)
			];
		}

		/**
		 * @param Player $player
		 * @return bool|int|string
		 */
		public function getRankUpRank(Player $player){
			$group = $this->rankUp->getRankUpDoesGroups()->getPlayerGroup($player);

			if($group !== false){
				return $group;
			}else{
				return "No Rank";
			}
		}

		/**
		 * @param Player $player
		 * @return bool|Rank|string
		 */
		public function getLevel(Player $player)
		{
			return $this->plevel->getLevel($player);
		}
		public function getXp(Player $player)
		{
			return $this->plevel->getXp($player);
		}
		public function getMaxLevel(Player $player)
		{
			return $this->plevel->getMaxLevel();
		}
		public function getMaxXp(Player $player)
		{
			return $this->plevel->getMaxXp($player);
		}
	}
}