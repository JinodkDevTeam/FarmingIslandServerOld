<?php

//ORIGINAL CODE: ChatThin by PresentKim
//https://github.com/PresentKim/ChatThin

declare(strict_types=1);

namespace NgLamVN\GameHandle\ChatThin;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class CT_PacketHandler implements Listener
{
    public const THIN_TAG = TextFormat::ESCAPE . "　";

    public function toThin(string $str): string
    {
        return preg_replace("/%*(([a-z0-9_]+\.)+[a-z0-9_]+)/i", "%$1", $str) . self::THIN_TAG;
    }

    /**
     * @param DataPacketSendEvent $event
     * @priority HIGHEST
     */
    public function onPacketSend(DataPacketSendEvent $event): void
    {
        $pk = $event->getPacket();
        if($pk instanceof TextPacket)
        {
            if($pk->type === TextPacket::TYPE_TIP || $pk->type === TextPacket::TYPE_POPUP || $pk->type === TextPacket::TYPE_JUKEBOX_POPUP)
                return;

            if($pk->type === TextPacket::TYPE_TRANSLATION)
            {
                $pk->message = $this->toThin($pk->message);
            }
            else
            {
                $pk->message .= self::THIN_TAG;
            }
        }
        elseif($pk instanceof AvailableCommandsPacket)
        {
            foreach($pk->commandData as $name => $commandData)
            {
                $commandData->commandDescription = $this->toThin($commandData->commandDescription);
            }
        }
    }
}
