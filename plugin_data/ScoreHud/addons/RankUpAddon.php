<?php
declare(strict_types = 1);

/**
 * @name RankUpAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\RankUpAddon
 * @depend RankUp
 */
namespace JackMD\ScoreHud\Addons
{

	use JackMD\ScoreHud\addon\AddonBase;
	use pocketmine\Player;
	use rankup\rank\Rank;
	use rankup\rank\RankStore;
	use rankup\RankUp;

	class RankUpAddon extends AddonBase{

		/** @var RankUp */
		private $rankUp;
		private $economyAPI;

		public function onEnable(): void{
			$this->rankUp = $this->getServer()->getPluginManager()->getPlugin("RankUp");
			$this->economyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{prison_rank}"            => $this->getRankUpRank($player),
				"{prison_next_rank_price}" => $this->getRankUpRankPrice($player),
				"{prison_next_rank}"       => $this->getRankUpNextRank($player),
				"{progress}"               => $this->getProgress($player)
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
		public function getRankUpNextRank(Player $player){
			$nextRank = $this->rankUp->getRankStore()->getNextRank($player);

			if($nextRank !== false){
				return $nextRank->getName();
			}else{
				return "Max Rank";
			}
		}
		public function getRankUpRankPrice(Player $player){
			$nextRankPrice = $this->rankUp->getRankStore()->getNextRank($player);

			if($nextRankPrice !== false){
				return $nextRankPrice->getPrice();
			}else{
				return "0";
			}
		}
		public function getProgress (Player $player){
			$nextRankPrice = $this->rankUp->getRankStore()->getNextRank($player);
			if($nextRankPrice == false)
				return "0";
			if (($nextRankPrice->getPrice()) == 0) return "0";
			$progress = ($this->economyAPI->myMoney($player) / $nextRankPrice->getPrice()) * 100;
			return (string)round($progress, 0, PHP_ROUND_HALF_DOWN);
		}
	}
}