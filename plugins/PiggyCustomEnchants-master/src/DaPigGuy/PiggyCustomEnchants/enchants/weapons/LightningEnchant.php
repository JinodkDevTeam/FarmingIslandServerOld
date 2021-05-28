<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCustomEnchants\enchants\weapons;

use DaPigGuy\PiggyCustomEnchants\enchants\CustomEnchant;
use DaPigGuy\PiggyCustomEnchants\enchants\ReactiveEnchantment;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Event;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\Player;

class LightningEnchant extends ReactiveEnchantment
{
    /** @var string */
    public $name = "Lightning";
    /** @var int */
    public $rarity = CustomEnchant::RARITY_MYTHIC;

    public function react(Player $player, Item $item, Inventory $inventory, int $slot, Event $event, int $level, int $stack): void
    {
        if ($event instanceof EntityDamageByEntityEvent) {
            $lightning = Entity::createEntity("PiggyLightning", $event->getEntity()->getLevel(), Entity::createBaseNBT($event->getEntity()));
            $lightning->setOwningEntity($player);
            $lightning->spawnToAll();
        }
    }
}