<?php
declare(strict_types=1);

namespace LamPocketVN\LpkTag;

use _64FF00\PureChat\PureChat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

use jojoe77777\FormAPI\SimpleForm;

class LpkTag extends PluginBase {
	/** @var PureChat $pureChat */
	private $pureChat;

	private $config;

	public $tags;

	public function onEnable() {
		$this->pureChat = $pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
		$this->getLogger()->info("§a Plugin đã chạy");
		$this->saveResource("tags.yml");
		$this->config = new Config($this->getDataFolder()."tags.yml", Config::YAML);
		$this->tags = $this->config->getAll();
	}

	public function onDisable() 
	{
		$this->getLogger()->info("§4 Plugin đã tắt");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		switch(strtolower($command->getName())) {
			case "settag":
				if(!$sender->hasPermission("lpktag.command.settag"))
				{
					$sender->sendMessage(TextFormat::RED."You not have permission to use this command !");
					return true;
				}
				if(!isset($args[0]))
				{
					$sender->sendMessage(TextFormat::RED . "[LpkTag] /settag <player> <tag name>");
					$sender->sendMessage(TextFormat::RED . "[LpkTag] /settag <player> custom <tag name>");
					return true;
				}
				$playerName = $args[0];
				$player = $this->getServer()->getPlayer($playerName);
				if($player === null) {
					$sender->sendMessage(TextFormat::RED . "[LpkTag] Player not found");
					return true;
				}
				if(!isset($args[1]))
				{
					$sender->sendMessage(TextFormat::RED . "[LpkTag] /settag" . $player->getName() . " <tag name>");
					$sender->sendMessage(TextFormat::RED . "[LpkTag] /settag" . $player->getName() . " custom <tag name>");
					return true;
				}
				$tags = $this->getConfig()->get("tags");
				if ($args[1] == "custom")
				{
					if(!isset($args[2]))
				            {
								$sender->sendMessage(TextFormat::RED . "[LpkTag] /settag" . $player . " custom <tag name>");
					            return true;
				            }
					$customtag = $args[2];
					$this->pureChat->setPrefix($customtag, $player, $this->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getLevel()->getName() : null);
		            $sender->sendMessage(TextFormat::GREEN . "[Lpktag] You set " . $player->getName() . " tag to " . $customtag . ".");	
		            $player->sendMessage(TextFormat::GREEN . "[LpkTag] Your tag has been updated to " . $customtag . ".");
				} 
				else
				{
				foreach ($tags as $tag=>$tagformat)
				{
						if ($args[1] == $tag )
						{
							$this->pureChat->setPrefix($tagformat, $player, $this->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getLevel()->getName() : null);
				            $sender->sendMessage(TextFormat::GREEN . "[Lpktag] You set " . $player->getName() . " tag to " . $tagformat . ".");
				            $player->sendMessage(TextFormat::GREEN . "[LpkTag] Your tag has been updated to " . $tagformat . ".");
							return true;
							break;
						}
				}
				$sender->sendMessage(TextFormat::RED . "[LpkTag] Tag not found");
				}
				return true;
				break;
			default:
				return true;
            case "tag":
                $this->TagForm($sender);
                return true;
                break;
		}
	}

	public function setTag(Player $player, $tagid)
    {
        $tagformat = $this->tags[$tagid];
        $this->pureChat->setPrefix($tagformat, $player, $this->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getLevel()->getName() : null);
    }
    public function setCustomTag(Player $player, $tag)
    {
        $this->pureChat->setPrefix($tag, $player, $this->pureChat->getConfig()->get("enable-multiworld-chat") ? $player->getLevel()->getName() : null);
    }

	public function TagForm(Player $player)
    {
        $form = new SimpleForm(
            function ($player, $data)
            {
                $id = $data;
                if ($id === 0)
                {
                    return;
                }
                if ($id === null)
                {
                    return;
                }
                if ($player->hasPermission("lpktag.tags." . $id))
                {
                    $this->setTag($player, $id);
                }
                else
                {
                    $player->sendMessage("You not have permission to use this tag.");
                }
            }
        );
        $form->setTitle("Tags");
        $form->addButton("Exit");
        foreach (array_keys($this->tags) as $tag)
        {
            if ($player->hasPermission("lpktag.tags." . $tag))
            {
                $form->addButton($this->tags[$tag] . "\n§l§aAvailable");
            }
            else
            {
                $form->addButton($this->tags[$tag] . "\n§l§cLocked");
            }
        }
        $form->sendToPlayer($player);
    }
}
