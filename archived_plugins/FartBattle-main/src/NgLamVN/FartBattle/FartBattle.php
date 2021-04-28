<?php

namespace NgLamVN\FartBattle;

use pocketmine\block\Block;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\level\Position;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\GenericSound;
use pocketmine\level\sound\NoteBlockSound;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Color;
use pocketmine\entity\Entity;

class FartBattle extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onTap(PlayerInteractEvent $event)
    {
        //Knockback gun

        $player = $event->getPlayer();
        if ($event->getItem()->getId() == Item::STONE_HOE)
        {
            $x = $player->getX();
            $y = $player->getY() + $player->getEyeHeight();
            $z = $player->getZ();
            $stepx = $player->getDirectionVector()->getX();
            $stepy = $player->getDirectionVector()->getY();
            $stepz = $player->getDirectionVector()->getZ();

            for ($i = 0; $i < 200; $i++)
            {
                $x = $x + $stepx;
                $y = $y + $stepy;
                $z = $z + $stepz;
                $player->getLevel()->addParticle(new FlameParticle(new Vector3($x, $y, $z)));
                if ($event->getPlayer()->getLevel()->getBlockAt($x,$y,$z)->getId() !== Block::AIR)
                {
                    break;
                }
                $bb = new AxisAlignedBB($x-0.3, $y-0.3, $z-0.3, $x+0.3, $y+0.3, $z+0.3);
                $entitys = $player->getLevel()->getNearbyEntities($bb);
                $pass = false;

                foreach ($entitys as $entity)
                {
                    if ($entity instanceof Living)
                    {
                        $ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_PROJECTILE, 0.5);
                        $entity->knockBack($player, 0, $entity->x - $player->x, $entity->z - $player->z, 0.5);
                        $entity->attack($ev);
                        $player->getLevel()->broadcastLevelEvent($player, LevelEventPacket::EVENT_SOUND_ORB, 1);
                        $pass = true;
                        break;
                    }
                }
                if ($pass) break;
            }
        }
    }

    public function onTap(PlayerInteractEvent $event)
    {
        //fake Explosive stick
        $player = $event->getPlayer();
        if ($event->getItem()->getId() !== Item::STICK)
        {
            return;
        }
        $x = $player->getX();
        $y = $player->getY() + $player->getEyeHeight();
        $z = $player->getZ();
        $stepx = $player->getDirectionVector()->getX();
        $stepy = $player->getDirectionVector()->getY();
        $stepz = $player->getDirectionVector()->getZ();

        for ($i = 0; $i < 200; $i++)
        {
            $x = $x + $stepx;
            $y = $y + $stepy;
            $z = $z + $stepz;
            $player->getLevel()->addParticle(new EnchantParticle(new Vector3($x,$y,$z), new Color(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255))));
            if (($event->getPlayer()->getLevel()->getBlockAt($x,$y,$z)->getId() !== Block::AIR) and ($event->getPlayer()->getLevel()->getBlockAt($x,$y,$z)->getId() !== Block::WATER) and ($event->getPlayer()->getLevel()->getBlockAt($x,$y,$z)->getId() !== Block::FLOWING_WATER))
            {
                $this->fakeExplosive(new Vector3($x, $y, $z), $player->getLevel(), $player);
                break;
            }
            $bb = new AxisAlignedBB($x-0.3, $y-0.3, $z-0.3, $x+0.3, $y+0.3, $z+0.3);
            $entitys = $player->getLevel()->getNearbyEntities($bb);
            foreach ($entitys as $entity)
            {
                if ($entity instanceof Living)
                {
                    $this->fakeExplosive(new Vector3($x, $y, $z), $player->getLevel(), $player);
                    break;
                }
            }
        }
    }

    public function fakeExplosive(Vector3 $vector3, Level $level, Player $damager)
    {
        $level->addParticle(new HugeExplodeParticle($vector3));
        $level->broadcastLevelSoundEvent($vector3, LevelSoundEventPacket::SOUND_EXPLODE);

        $explosionSize = 4; //around 4 block
        $minX = (int) floor($vector3->x - $explosionSize - 1);
        $maxX = (int) ceil($vector3->x + $explosionSize + 1);
        $minY = (int) floor($vector3->y - $explosionSize - 1);
        $maxY = (int) ceil($vector3->y + $explosionSize + 1);
        $minZ = (int) floor($vector3->z - $explosionSize - 1);
        $maxZ = (int) ceil($vector3->z + $explosionSize + 1);

        $explosionBB = new AxisAlignedBB($minX, $minY, $minZ, $maxX, $maxY, $maxZ);
        $entitys = $level->getNearbyEntities($explosionBB);
        foreach ($entitys as $entity)
        {
            if ($entity instanceof Living)
            {
                $ev = new EntityDamageByEntityEvent($damager, $entity, EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, 20);
                $entity->attack($ev);
            }
        }
    }


    public function onSneak(PlayerToggleSneakEvent $event)
    {
        //Shooting a beam and deal damage to nearby entity.

        $player = $event->getPlayer();
        if (!$event->isSneaking())
        {
            return;
        }
        $x = $player->getX();
        $y = $player->getY() + 0.5;
        $z = $player->getZ();
        $stepx = $player->getDirectionVector()->getX();
        $stepy = 0;
        $stepz = $player->getDirectionVector()->getZ();

        for ($i = 0; $i < 200; $i++)
        {
            $x = $x - $stepx;
            $y = $y - $stepy;
            $z = $z - $stepz;
            $player->getLevel()->addParticle(new ExplodeParticle(new Vector3($x,$y,$z)));
            if ($event->getPlayer()->getLevel()->getBlockAt($x,$y,$z)->getId() !== Block::AIR)
            {
                break;
            }
            $bb = new AxisAlignedBB($x-1, $y-1, $z-1, $x+1, $y+1, $z+1);
            $entitys = $player->getLevel()->getNearbyEntities($bb);
            foreach ($entitys as $entity)
            {
                if ($entity instanceof Living)
                {
                    if ($entity instanceof Player)
                    {
                        if ($entity->getName() !== $player->getName())
                        {
                            $ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, 5);
                            $entity->attack($ev);
                        }
                    } else
                        {
                            $ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, 5);
                            $entity->attack($ev);
                        }
                }
            }
        }
    }
    /*public function onSneak (PlayerToggleSneakEvent $event)
    {

        //Booommm !!!!

        $player = $event->getPlayer();
        if (!$event->isSneaking())
        {
            return;
        }
        $vct = $player->getDirectionVector();
        $player->setMotion(new Vector3($vct->getX() * 3, 1.25, $vct->getZ() * 3));
        $player->getLevel()->addParticle(new HugeExplodeSeedParticle($player->asVector3()));
    }*/

    /*public function onSneak (PlayerToggleSneakEvent $event)
    {

        //Shooting falling block mode

        $player = $event->getPlayer();
        if (!$event->isSneaking())
        {
            return;
        }
        $amount = 5;
        $anglesBetween = (45 / ($amount - 1)) * M_PI / 180;
        $pitch = ($player->pitch + 90) * M_PI / 180;
        $yaw = ($player->yaw + 90 - 45 / 2) * M_PI / 180;
        $multiply = 1.5;
        for ($i = 1; $i <= $amount; $i++)
        {
            $nbt = Entity::createBaseNBT($player->asVector3());
            $nbt->setInt("TileID", Block::WOOL);
            $nbt->setByte("Data", 12);
            $entity = Entity::createEntity(Entity::FALLING_BLOCK, $player->getLevel(), $nbt);
            $entity->spawnToAll();
            $Direction = new Vector3(-(sin($pitch) * cos($yaw + $anglesBetween * $i))*$multiply, 0.5, -(sin($pitch) * sin($yaw + $anglesBetween * $i))*$multiply);
            $entity->setMotion($Direction);
        }
    }*/

}