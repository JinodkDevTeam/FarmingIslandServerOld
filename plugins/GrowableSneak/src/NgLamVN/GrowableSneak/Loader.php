<?php

namespace NgLamVN\GrowableSneak;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\level\generator\object\Tree;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Random;

class Loader extends PluginBase implements Listener
{
    const RADIUS = 5;

    public function onEnable()
    {
        parent::onEnable(); // TODO: Change the autogenerated stub
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onSneak (PlayerToggleSneakEvent $event)
    {
        if ($event->isCancelled())
        {
            return;
        }
        if (!$event->isSneaking())
        {
            true;
        }
        $this->getArea($event->getPlayer()->asPosition());
    }

    public function growTree(Block $block)
    {
        if (!($block instanceof Sapling))
        {
            return;
        }
        if (mt_rand(1,20) === 5) //TODO: Random Chance :)
        {
            Tree::growTree($block->getLevelNonNull(), $block->x, $block->y, $block->z, new Random(mt_rand()), $block->getVariant());
        }
        else
        {
            $pos = $block->asVector3();
            $pos->y++;
            $block->getLevelNonNull()->addParticle(new HappyVillagerParticle($pos));
        }
    }

    public function getArea(Position $position)
    {
        $x = $position->getX();
        $y = $position->getY();
        $z = $position->getZ();
        $level = $position->getLevel();
        $radius = self::RADIUS;
        for ($i = $x - $radius; $i <= $x + $radius;$i++)
            for ($j = $y - $radius; $j <= $y + $radius;$j++)
                for ($k = $z - $radius; $k <= $z + $radius;$k++)
                {
                    $block = $level->getBlockAt($i, $j, $k);
                    $this->growTree($block);
                }
    }
}