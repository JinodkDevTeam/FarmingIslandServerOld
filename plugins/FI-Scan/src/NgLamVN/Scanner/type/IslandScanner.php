<?php

namespace NgLamVN\Scanner\type;

use MyPlot\MyPlot;
use NgLamVN\Scanner\Loader;
use pocketmine\block\Chest;
use pocketmine\Server;

class IslandScanner
{
    public const UNBANNED_BLOCKS_ID = [
        0, 1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 22, 24, 26, 27, 31, 35, 37, 38, 41, 42, 43, 44, 45,
        50, 53, 54, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 70, 71, 72, 77, 79, 81, 82, 83, 85, 86, 89, 91, 92, 96,
        98, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 118, 128, 129, 133, 134, 135, 136, 139, 140, 141, 142, 143, 145,
        147, 148, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 167, 170, 171, 172, 173, 174, 175, 176, 177, 183, 184,
        185, 186, 187, 190, 191, 193, 194, 195, 196, 197, 198, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 231, 232,
        233, 234, 235, 236, 237, 241, 244, 253, 254, 395, 396, 397, 398, 399, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409,
        410, 421, 435, 436, 437, 438, 440, 441, 442, 443, 444, 445, 446, 447, 448, 467
    ]; //ITS FKING LONG

    private Loader $loader;

    public int $x;

    public int $y;

    public int $replaced = 0;

    public function __construct(Loader $loader, int $x, int $y)
    {
        $this->loader = $loader;
        $this->x = $x;
        $this->y = $y;

        $this->scan();
    }

    public function scan()
    {
        $level = Server::getInstance()->getLevelByName("island");
        $plot = MyPlot::getInstance()->getProvider()->getPlot("island", $this->x, $this->y);
        $bb = MyPlot::getInstance()->getPlotBB($plot);
        for ($x = $bb->minX; $x <= $bb->maxX; $x++)
            for ($y = $bb->minY + 1; $y <= $bb->maxY; $y++)
                for ($z = $bb->minZ; $z <= $bb->maxZ; $z++)
                {
                    $block = $level->getBlockAt($x, $y, $z);
                    if ($block instanceof Chest)
                    {
                        new ChestScanner($this->loader, $block);
                        continue;
                    }
                    if (!in_array($block->getId(), self::UNBANNED_BLOCKS_ID))
                    {
                        $level->setBlockIdAt($x, $y, $z, 0);
                        $level->setBlockDataAt($x, $y, $z, 0);
                        $this->replaced++;
                    }
                }
        if ($this->replaced > 0)
        {
            $this->loader->getLogger()->warning("Clear " .$this->replaced . " banned block in island " .$this->x . ";" .$this->y . ". Island Owner: " . $plot->owner);
        }
    }

}