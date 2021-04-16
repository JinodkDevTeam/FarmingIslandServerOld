<?php


namespace NgLamVN\FIScore;

use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\event\ServerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use onebone\economyapi\event\money\MoneyChangedEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;

class EventListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        (new ServerTagUpdateEvent(new ScoreTag("farmingislandscore.online", strval(count(Server::getInstance()->getOnlinePlayers())))))->call();
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        (new ServerTagUpdateEvent(new ScoreTag("farmingislandscore.online", strval(count(Server::getInstance()->getOnlinePlayers())))))->call();
    }

    public function onMoneyChange(MoneyChangedEvent $event)
    {
        $username = $event->getUsername();

        if(is_null($username))
        {
            return;
        }

        $player = Server::getInstance()->getPlayer($username);

        if($player instanceof Player && $player->isOnline())
        {
            (new PlayerTagUpdateEvent($player, new ScoreTag("farmingislandscore.money", $event->getMoney())))->call();
        }
    }
}
