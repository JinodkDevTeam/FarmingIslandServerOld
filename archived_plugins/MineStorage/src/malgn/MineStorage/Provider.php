<?php

namespace malgn\MineStorage;


use pocketmine\Player;

class Provider
{
    /** @var Loader */
    private $loader;
    /** @var \SQLite3 */
    private $db;
    /** @var \SQLite3Stmt $sqlGetData */
    protected $sqlGetData;
    /** @var \SQLite3Stmt $sqlSetData */
    protected $sqlSetData;
    /** @var \SQLite3Stmt $sqlRegisterData */
    protected $sqlRegisterData;
    /** @var \SQLite3Stmt $sqlRemoveData */
    protected $sqlRemoveData;


    public function __construct(Loader $loader)
    {
        $allowblocks = [1,2,3];

        $this->loader = $loader;

        $this->db = new \SQLite3($loader->getDataFolder() . "storage.db");

        $this->db->exec("CREATE TABLE IF NOT EXISTS storage (player TEXT PRIMARY KEY );");

        foreach ($allowblocks as $blockid)
        {
            try {
                $this->db->exec("ALTER TABLE storage ADD ".$blockid." INTEGER;");
            }
            catch (\Exception $e) {
                //NOTHING.
            }
        }
        $stmt = $this->db->prepare("SELECT * FROM storage WHERE player = :player");
        if ($stmt === false) throw new \Exception();
        $this->sqlGetData = $stmt;

        $stmt = $this->db->prepare("UPDATE storage SET :block = :value WHERE player = :player;");
        if ($stmt === false) throw new \Exception();
        $this->sqlSetData = $stmt;

        $stmt = $this->db->prepare("INSERT INTO storage (player) VALUES (:player);");
        if ($stmt === false) throw new \Exception();
        $this->sqlRegisterData = $stmt;

        $stmt = $this->db->prepare("DELETE FROM storage WHERE player = :player");
        if ($stmt === false) throw new \Exception();
        $this->sqlRemoveData = $stmt;
    }

    public function getPlayerData(Player $player): array
    {
        $name = $player->getName();

        $stmt = $this->sqlGetData;
        $stmt->bindValue(":player", $name, SQLITE3_TEXT);
        $stmt->reset();
        $result = $stmt->execute();

        if ($result !== false and ($data = $result->fetchArray(SQLITE3_ASSOC)) !== false)
        {
            return $data;
        }
        else return [];
    }

    public function updateData (Player $player, int $blockid, int $value)
    {
        $name = $player->getName();

        $stmt = $this->sqlSetData;

        $stmt->bindValue(":block", $blockid, SQLITE3_INTEGER);
        $stmt->bindValue(":value", $value, SQLITE3_INTEGER);
        $stmt->bindValue(":player", $name, SQLITE3_TEXT);
        $stmt->reset();
        $result = $stmt->execute();
    }

    public function registerData(Player $player)
    {
        $name = $player->getName();

        $stmt = $this->sqlRegisterData;

        $stmt->bindValue(":player", $name, SQLITE3_TEXT);

        $stmt->reset();
        $result = $stmt->execute();
    }

    public function removeData(Player $player)
    {
        $name = $player->getName();

        $stmt = $this->sqlRemoveData;

        $stmt->bindValue(":player", $name);

        $stmt->reset();
        $result = $stmt->execute();
    }

    public function close()
    {
        $this->db->close();
    }
}