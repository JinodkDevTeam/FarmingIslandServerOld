<?php

/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2021  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace czechpmdevs\multiworld\generator\normal;

use pocketmine\level\biome\Biome;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\utils\Random;

class BiomeSelector {

    /** @var Simplex */
    public Simplex $temperature;
    /** @var Simplex */
    public Simplex $rainfall;
    /** @var Simplex */
    public Simplex $ocean;
    /** @var Simplex */
    public Simplex $hills;
    /** @var Simplex */
    public Simplex $smallHills;
    /** @var Simplex */
    public Simplex $river;

    public function __construct(Random $random) {
        $this->temperature = new Simplex($random, 3, 1 / 4, 1 / 2048); //2 oct
        $this->rainfall = new Simplex($random, 3, 1 / 4, 1 / 2048); // 2 oct
        $this->ocean = new Simplex($random, 6, 1 / 2, 1 / 2048);
        $this->hills = new Simplex($random, 6, 1 / 2, 1 / 2048);
        $this->smallHills = new Simplex($random, 2, 1 / 32, 1 / 256);
        $this->river = new Simplex($random, 6, 1 / 2, 1 / 1024);
    }

    public function pickBiome(float $x, float $z): Biome {
        if ($this->getOcean($x, $z) < -0.2) {
            if ($this->getTemperature($x, $z) < 0) {
                return BiomeManager::getBiome(BiomeManager::FROZEN_OCEAN);
            }

            if ($this->getOcean($x, $z) > -0.4) {
                if ($this->getTemperature($x, $z) > 0.8) {
                    return BiomeManager::getBiome(BiomeManager::SWAMP);
                }
            }

            if ($this->getOcean($x, $z) > -0.23) {
                if ($this->getSmallHills($x, $z) > 0) {
                    return BiomeManager::getBiome(BiomeManager::BEACH);
                }
            }
            return BiomeManager::getBiome(BiomeManager::OCEAN);
        }

        if (abs($this->getRiver($x, $z)) < 0.06) {
            if ($this->getTemperature($x, $z) < 0) {
                return BiomeManager::getBiome(BiomeManager::FROZEN_RIVER);
            }
            return BiomeManager::getBiome(BiomeManager::RIVER);
        }

        $temperature = $this->getTemperature($x, $z);
        $rainfall = $this->getRainfall($x, $z);
        $hills = $this->getSmallHills($x, $z);

        if ($rainfall < 0.4) {
            if ($temperature > 0.5) {
                if ($hills < 0) {
                    return BiomeManager::getBiome(BiomeManager::DESERT);
                }

                return BiomeManager::getBiome(BiomeManager::DESERT_HILLS);
            }

            if ($hills < 0) {
                return BiomeManager::getBiome(BiomeManager::SAVANNA);
            }

            return BiomeManager::getBiome(BiomeManager::SAVANNA_PLATEAU);
        }

        if ($rainfall < 0.8) {
            if ($temperature < 0.3) {
                if ($hills > 0) {
                    return BiomeManager::getBiome(BiomeManager::FOREST_HILLS);
                }
                return BiomeManager::getBiome(BiomeManager::FOREST);
            }

            if ($temperature < 0.6) {
                if ($hills > 0) {
                    return BiomeManager::getBiome(BiomeManager::TALL_BIRCH_FOREST);
                }
                return BiomeManager::getBiome(BiomeManager::BIRCH_FOREST);
            }

            if ($hills > 0) {
                return BiomeManager::getBiome(BiomeManager::ROOFED_FOREST_HILLS);
            }
            return BiomeManager::getBiome(BiomeManager::ROOFED_FOREST);
        }

        if ($rainfall < 1.2) {
            if ($temperature < 0) {
                return BiomeManager::getBiome(BiomeManager::ICE_MOUNTAINS);
            }
            if ($temperature < 0.4) {
                if ($hills > 0.5) {
                    return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS_MUTATED);
                }
                return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS);
            }
            if ($temperature < 0.8) {
                return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS_EDGE);
            }
        }

        return BiomeManager::getBiome(BiomeManager::PLAINS);
    }

    /**
     * @return float|int
     */
    public function getOcean(float $x, float $z) {
        return $this->ocean->noise2D($x, $z, true);
    }

    /**
     * @return float|int
     */
    public function getTemperature(float $x, float $z) {
        return abs(round($this->temperature->noise2D($x, $z, true) * M_PI / 3 * 2, 1));
    }

    /**
     * @return float|int
     */
    public function getSmallHills(float $x, float $z) {
        return $this->smallHills->noise2D($x, $z, true);
    }

    /**
     * @return float|int
     */
    public function getRiver(float $x, float $z) {
        return $this->river->noise2D($x, $z, true);
    }

    /**
     * @return float|int
     */
    public function getRainfall(float $x, float $z) {
        return abs(round($this->rainfall->noise2D($x, $z, true) * M_PI / 3 * 2, 1));
    }
}