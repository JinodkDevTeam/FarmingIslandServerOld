<?php

namespace NgLamVN\DeathSwap;

use pocketmine\Server;

class FileManager
{
    public function copyWorld(string $world1, string $world2)
    {
        $folder1 = Server::getInstance()->getLevelByName($world1)->getFolderName();
        $folder2 = $world2;

        $path1 = Server::getInstance()->getDataPath() . "worlds" . DIRECTORY_SEPARATOR . $folder1;
        $path2 = Server::getInstance()->getDataPath() . "worlds" . DIRECTORY_SEPARATOR . $folder2;

        Server::getInstance()->loadLevel($world2);
    }

    public static function copyr($source, $dest)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            @mkdir($dest, 0777, true);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::copyr("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();
        return true;
    }
}
