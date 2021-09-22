<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\entity\InvalidSkinException;
use pocketmine\entity\Skin;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class LegacySkinAdapter implements SkinAdapter{

	public function toSkinData(Skin $skin) : SkinData{
		return new SkinData(
			$skin->getSkinId(),
			"", //TODO: playfab ID
			$skin->getResourcePatch(),
			$skin->getSkinImage(),
			$skin->getAnimations(),
			$skin->getCape()->getImage(),
			$skin->getGeometryData(),
            ProtocolInfo::MINECRAFT_VERSION_NETWORK,
			$skin->getAnimationData(),
            $skin->getCape()->getId(),
            null,
            $skin->getArmSize(),
            $skin->getSkinColor(),
            $skin->getPersonaPieces(),
            $skin->getPieceTintColors(),
            $skin->isVerified(),
            $skin->isPremium(),
            $skin->isPersona(),
            $skin->getCape()->isOnClassicSkin()
		);

	}

	public function fromSkinData(SkinData $data) : Skin{
		if($data->isPersona()){
			return new Skin("Standard_Custom", str_repeat(random_bytes(3) . "\xff", 4096));
		}

		$capeData = $data->isPersonaCapeOnClassic() ? "" : $data->getCapeImage()->getData();

		$resourcePatch = json_decode($data->getResourcePatch(), true);
		if(is_array($resourcePatch) && isset($resourcePatch["geometry"]["default"]) && is_string($resourcePatch["geometry"]["default"])){
			$geometryName = $resourcePatch["geometry"]["default"];
		}else{
			throw new InvalidSkinException("Missing geometry name field");
		}

		return (new Skin(
			$data->getSkinId(),
			"",
			"",
			$data->getResourcePatch(),
			$data->getGeometryData()
		))->setSkinImage($data->getSkinImage())
		->setCape(new Cape($data->getCapeId(), $data->getCapeImage(), $data->isPersonaCapeOnClassic()))
		->setAnimations($data->getAnimations())
		->setAnimationData($data->getAnimationData())
		->setPremium($data->isPremium())
		->setPersona($data->isPersona())
		->setArmSize($data->getArmSize())
		->setSkinColor($data->getSkinColor())
		->setPersonaPieces($data->getPersonaPieces())
		->setPieceTintColors($data->getPieceTintColors())
		->setVerified($data->isVerified());
	}
}
