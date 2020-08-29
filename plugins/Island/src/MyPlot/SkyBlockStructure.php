<?php

namespace MyPlot;

use pocketmine\math\Vector3;
use pocketmine\level\ChunkManager;
use pocketmine\level\SimpleChunkManager;
use pocketmine\block\Block;
use pocketmine\level\generator\populator\Populator;
use pocketmine\utils\Random;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\NBT;
use pocketmine\level\generator\Generator;
use pocketmine\level\format\Chunk;
use pocketmine\tile\Chest;
use pocketmine\item\Item;

class SkyBlockStructure extends Populator{
	public $generator = null;

	public function __construct(Generator $gen){
		$this->generator = $gen;
	}

	/**
	 *
	 * @param ChunkManager $level 
	 * @param Chunk $chunk 
	 * @param  $Xofchunk 
	 * @param  $Zofchunk 
	 */
	public static function placeObject(ChunkManager $level, $chunk, $Xofchunk, $Zofchunk)
    {
        $vec = new Vector3($chunk->getX() * 16 + $Xofchunk, 0, $chunk->getZ() * 16 + $Zofchunk);
        $vec = $vec->subtract(7, 0, 7); // fix offset
        for ($i = 6; $i < 9; $i++)
            for ($j = 6; $j < 9; $j++) {
                $level->setBlockIdAt($vec->x + $i, 64, $vec->z + $j, Block::PLANKS);
            }
    }

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$chunk = $level->getChunk($chunkX, $chunkZ);
		$shape = $this->generator->getShape($chunkX << 4, $chunkZ << 4);
		for($Z = 0; $Z < 16; ++$Z){
			for($X = 0; $X < 16; ++$X){
				$type = $shape[($Z << 4) | $X];
				if($type === MyPlotGenerator::ISLAND){
					self::placeObject($level, $chunk, $X, $Z);
				}
			}
		}
	}
}