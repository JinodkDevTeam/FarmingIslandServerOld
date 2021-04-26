<?php

namespace NgLamVN\FIScore;

use Ifera\ScoreHud\event\TagsResolveEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\Server;

class TagResloveListener implements Listener
{
    public function __construct()
    {
    }

    public function onTagReslove(TagsResolveEvent $event)
    {
        $player = $event->getPlayer();
        $tag = $event->getTag();
        $name = $tag->getName();

        switch ($name)
        {
            case "fi-scoreloader.money":
                $value = EconomyAPI::getInstance()->myMoney($player);
                $tag->setValue($value);
                break;
            case "fi-scoreloader.online":
                $online = count(Server::getInstance()->getOnlinePlayers());
                $tag->setValue($online);
            case "fi-scoreloader.max_online":
                $max_online = Server::getInstance()->getMaxPlayers();
                $tag->setValue($max_online);
                break;
            case "fi-scoreloader.ping":
                $ping = $player->getPing();
                $tag->setValue($ping);
                break;
            case "fi-scoreloader.name":
                $pname = $player->getName();
                $tag->setValue($pname);
                break;
        }
    }
}
