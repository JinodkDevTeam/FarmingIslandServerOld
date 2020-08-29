<?php
declare(strict_types = 1);

/**
 * @name PointApiAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\PointApiAddon
 * @depend PointAPI
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use onebone\pointapi\PointAPI;
	use pocketmine\Player;

	class PointApiAddon extends AddonBase{

		/** @var PointAPI */
		private $pointAPI;

		public function onEnable(): void{
			$this->pointAPI = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{point}" => $this->pointAPI->myPoint($player)
			];
		}
	}
}