<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\CoinSystem;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\GameHandle\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class CoinCommand extends PluginCommand
{
    public CoinSystem $system;
    /** @var array */
    public array $users;

    public function __construct(Core $plugin, CoinSystem $system)
    {
        $this->system = $system;
        parent::__construct("coin", $plugin);
        $this->setDescription("Coin Manager");
        $this->setPermission("gh.coin.use");
    }

    public function getSystem(): CoinSystem
    {
        return $this->system;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player)
        {
            $this->CoinForm($sender);
            return;
        }
        if ($args[0] == "add")
        {
            if (isset($args[1]))
            {
                if (isset($args[2]))
                {
                    if (is_numeric($args[2]))
                    {
                        $player = $this->getSystem()->getCore()->getServer()->getPlayer($args[1]);
                        if ($player !== null)
                        {
                            $this->getSystem()->addCoin($player, $args[2]);
                            $player->sendMessage("Bạn được nhận thêm " . $args[2] . " coin");
                        }
                    }
                }
            }
        }
    }

    public function CoinForm (Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            if ($data == null)
            {
                return;
            }
            switch ($data)
            {
                case 0:
                    break;
                case 1:
                    $this->Fpay($player);
                    break;
                case 2:
                    $this->Ftop($player);
                    break;
                case 3:
                    break;
                case 4:
                    $this->Fset($player);
                    break;
                case 5:
                    $this->Fadd($player);
                    break;
                case 6:
                    $this->Freduce($player);
                    break;
            }
        });
        $form->setTitle("Coin Manager");
        $form->setContent("Your Coin: " . $this->getSystem()->getCoin($player));
        $form->addButton("EXIT");
        $form->addButton("Pay Coin");
        $form->addButton("Top Coin");
        $form->addButton("Buy Coin");
        if ($player->hasPermission("gh.coin.set"))
        {
            $form->addButton("Set Coin");
        }
        if ($player->hasPermission("gh.coin.add"))
        {
            $form->addButton("Add Coin");
        }
        if ($player->hasPermission("gh.coin.reduce"))
        {
            $form->addButton("Reduce Coin");
        }
        $player->sendForm($form);
    }
    public function Fpay(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            if (!isset($data[1])) return;
            $taget = Server::getInstance()->getPlayer($data[0]);
            if ($taget == null)
            {
                $taget = $data[0];
                if (!$this->system->IsHasData($taget)) {
                    $player->sendMessage("Player Not Found !");
                    return;
                }
                if ($taget == $player->getName())
                {
                    $player->sendMessage("Dont pay to yourself");
                    return;
                }
            }
            else
            {
                if ($taget->getName() == $player->getName())
                {
                    $player->sendMessage("Dont pay to yourself");
                    return;
                }
            }
            if (!is_numeric($data[1]))
            {
                $player->sendMessage("Amount must be numeric !");
                return;
            }
            if ($data[1] > $this->system->getCoin($player))
            {
                $player->sendMessage("You are not have enought coin to pay");
                return;
            }
            if ($data[1] < 0)
            {
                $player->sendMessage("Amount must be >= 0");
                return;
            }
            $this->system->addCoin($taget, $data[1]);
            $this->system->reduceCoin($player, $data[1]);
        });
        $form->setTitle("Pay Coin");
        $form->addInput("Player Name", "Steve123");
        $form->addInput("Amount", "123456789");
        $player->sendForm($form);
    }
    public function Ftop(Player $player, $page = 1)
    {
        $this->buildTop();
        $count = count($this->users);
        $pages = ceil($count / 5);
        $form = new CustomForm(function (Player $player, $data) use ($count, $pages)
        {
            if (!isset($data[5])) return;
            if (!is_numeric($data[5])) return;
            if ($data[5] > $pages) return;
            $this->Ftop($player, $data[5]);
        });
        $form->setTitle("Top coin page " . $page . "/" . $pages);

        $max = 5 + ($page * 5) - 5;
        $min = 1 + ($page * 5) - 5;

        for ($i = $min; $i <= $max; $i++)
        {
            if (isset($this->users[$i]))
            {
                $form->addLabel("[" . $i . "]" . $this->users[$i] . ": " . $this->getSystem()->getCoin($this->users[$i]) . " point");
            }
        }
        $form->addInput("Page:", "12345");
        $player->sendForm($form);
        return;
    }
    public function Fadd(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            if (!isset($data[1])) return;
            $taget = Server::getInstance()->getPlayer($data[0]);
            if ($taget == null)
            {
                $taget = $data[0];
                if (!$this->system->IsHasData($taget))
                {
                    $player->sendMessage("Player Not Found !");
                    return;
                }
            }
            if (!is_numeric($data[1]))
            {
                $player->sendMessage("Amount must be numeric !");
                return;
            }
            $this->system->addCoin($taget, $data[1]);
        });
        $form->setTitle("Add Coin");
        $form->addInput("Player Name", "Steve123");
        $form->addInput("Amount", "123456789");
        $player->sendForm($form);
    }
    public function Fset(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            if (!isset($data[1])) return;
            $taget = Server::getInstance()->getPlayer($data[0]);
            if ($taget == null)
            {
                $taget = $data[0];
                if (!$this->system->IsHasData($taget))
                {
                    $player->sendMessage("Player Not Found !");
                    return;
                }
            }
            if (!is_numeric($data[1]))
            {
                $player->sendMessage("Amount must be numeric !");
                return;
            }
            $this->system->setCoin($taget, $data[1]);
        });
        $form->setTitle("Set Coin");
        $form->addInput("Player Name", "Steve123");
        $form->addInput("Amount", "123456789");
        $player->sendForm($form);
    }
    public function Freduce(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0])) return;
            if (!isset($data[1])) return;
            $taget = Server::getInstance()->getPlayer($data[0]);
            if ($taget == null)
            {
                $taget = $data[0];
                if (!$this->system->IsHasData($taget))
                {
                    $player->sendMessage("Player Not Found !");
                    return;
                }
            }
            if (!is_numeric($data[1]))
            {
                $player->sendMessage("Amount must be numeric !");
                return;
            }
            $this->system->reduceCoin($taget, $data[1]);
        });
        $form->setTitle("Reduce Coin");
        $form->addInput("Player Name", "Steve123");
        $form->addInput("Amount", "123456789");
        $player->sendForm($form);
    }

    public function buildTop()
    {
        $data = $this->getSystem()->data;
        $this->users = [""];
        arsort($data);
        foreach (array_keys($data) as $user)
        {
            array_push($this->users, $user);
        }
    }
}
