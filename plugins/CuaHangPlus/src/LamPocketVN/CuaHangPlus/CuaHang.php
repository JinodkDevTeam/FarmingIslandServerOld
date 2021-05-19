<?php
// THIS PLUGIN RECODED BY LAMPOCKETVN.
// From CuaHangUI moded by LamPocketVN
// Dev Using PhpStorm :3


namespace LamPocketVN\CuaHangPlus;

use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\item\ItemFactory;
use pocketmine\inventory\transaction\action\SlotChangeAction;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\ModalForm;

use onebone\economyapi\EconomyAPI;
use NgLamVN\GameHandle\CoinSystem\CoinSystem;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;

use Closure;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;

/**
 * Class CuaHang
 * @package LamPocketVN\CuaHangPlus
 */
class CuaHang extends PluginBase implements Listener
{
    /**
     * @var $shop
     * @var $setting
     */
	private $shop;
	private $setting;

    /**
     * @return mixed
     */
	public function getShop()
	{
		return $this->shop->getAll();
	}

    /**
     * @return mixed
     */
	public function getStg()
	{
		return $this->setting->getAll();
	}

    /**
     * @param $id
     * @return mixed
     */
	public function getItem($id): Item
	{
		$exn = explode(':', $id);
		$idi = $exn[0];
		$metai = $exn[1];
		$counti = $exn[2];
		$item = Item::get($idi, $metai, $counti);
		return $item;
	}

    /**
     * @param Item $item
     * @param int $level
     * @param $enchantment
     */
	public function enchantItem(Item $item, int $level, $enchantment): void
	{
        if(is_string($enchantment)){
            $ench = Enchantment::getEnchantmentByName((string) $enchantment);
            if($this->piggyCE !== null && $ench === null){
                $ench = CustomEnchantManager::getEnchantmentByName((string) $enchantment);
            }
            if($this->piggyCE !== null && $ench instanceof CustomEnchantManager){
                $this->piggyCE->addEnchantment($item, $ench->getName(), (int) $level);
            }else{
                $item->addEnchantment(new EnchantmentInstance($ench, (int) $level));
            }
        }
        if(is_int($enchantment)){
            $ench = Enchantment::getEnchantment($enchantment);
            $item->addEnchantment(new EnchantmentInstance($ench, (int) $level));
        }
    }

    public function DecodeItem(string $data): Item
    {
        $edian = new BigEndianNBTStream();
        $nbt = $edian->readCompressed(hex2bin($data));
        $item = Item::nbtDeserialize($nbt);

        return $item;
    }
	
	public function onEnable()
	{
		@mkdir($this->getDataFolder());
		$this->saveResource("setting.yml");
		$this->setting = new Config($this->getDataFolder() . "setting.yml", Config::YAML);
		
		@mkdir($this->getDataFolder());
		$this->saveResource("shop.yml");
		$this->shop = new Config($this->getDataFolder() . "shop.yml", Config::YAML);
		
		$this->piggyCE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		
		if(!InvMenuHandler::isRegistered())
		{
			InvMenuHandler::register($this);
		}
		
		$this->getLogger()->info("Plugin enabled! Plugin by LamPocketVN");
	}

    /**
     * @param CommandSender $sender
     * @param Command $cmd
     * @param string $label
     * @param array $args
     * @return bool
     */
	public function onCommand (CommandSender $sender, Command $cmd, string $label, array $args): bool
	{
		switch (strtolower($cmd->getName()))
		{
			case "cuahang":
				$this->openForm($sender);
			return true;
			break;
		}
		return true;
	}

    /**
     * @param Player $player
     */
	public function openForm(Player $player)
	{
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName($this->getStg()["shop-name"]);
		$menu->setListener(InvMenu::readonly(Closure::fromCallable([$this, "MenuListener"])));
		$menuinv = $menu->getInventory();
		
		foreach (array_keys($this->getShop()) as $id)
		{
		    if (!isset($this->getShop()[$id]["IC_Code"]))
            {
                $item = $this->getItem($this->getShop()[$id]['Item']['Id']);
                if ($this->getShop()[$id]['Item']['Name'] != "")
                {
                    $item->setCustomName($this->getShop()[$id]['Item']['Name']);
                }
                if ($this->getShop()[$id]['Item']['Lore'] != "")
                {
                    if ($this->getShop()[$id]['Sell']['money'] === "true")
                    {
                        $item->setLore(array($this->getShop()[$id]['Item']['Lore'] . "\n\n" . str_replace("{price}", $this->getShop()[$id]['Sell']['price'], $this->getStg()['item-price']['money'])));
                    }
                    if ($this->getShop()[$id]['Sell']['coin'] === "true")
                    {
                        $item->setLore(array($this->getShop()[$id]['Item']['Lore'] . "\n\n" . str_replace("{price}", $this->getShop()[$id]['Sell']['price'], $this->getStg()['item-price']['coin'])));
                    }
                }
                foreach (array_keys($this->getShop()[$id]['Item']['Enchantments']) as $enchant)
                {
                    $ide = $this->getShop()[$id]['Item']['Enchantments'][$enchant]['Id'];
                    $lve = $this->getShop()[$id]['Item']['Enchantments'][$enchant]['Level'];
                    $this->enchantItem($item, $lve, $ide);
                }
                if (isset($this->getShop()[$id]['Item']['Unbreakable']))
                {
                    if ($this->getShop()[$id]['Item']['Unbreakable'] == "true")
                    {
                        $nbt = $item->getNamedTag();
                        $nbt->setByte("Unbreakable", 1);
                        $item->setNamedTag($nbt);
                    }
                }
            }
            else
            {
                $item = $this->DecodeItem($this->getShop()[$id]["IC_Code"]);

                if ($this->getShop()[$id]['Item']['Lore'] != "")
                {
                    if ($this->getShop()[$id]['Sell']['money'] === "true")
                    {
                        $item->setLore(array($this->getShop()[$id]['Item']['Lore'] . "\n\n" . str_replace("{price}", $this->getShop()[$id]['Sell']['price'], $this->getStg()['item-price']['money'])));
                    }
                    if ($this->getShop()[$id]['Sell']['coin'] === "true")
                    {
                        $item->setLore(array($this->getShop()[$id]['Item']['Lore'] . "\n\n" . str_replace("{price}", $this->getShop()[$id]['Sell']['price'], $this->getStg()['item-price']['coin'])));
                    }
                }
            }
			$menuinv->setItem($id-1, $item);
		}
		
		$menu->send($player);
	}

    /**
     * @param Player $player
     * @param Item $itemClicked
     * @param Item $itemClickedWith
     * @param SlotChangeAction $action
     * @return bool
     */
	public function MenuListener (DeterministicInvMenuTransaction $transaction): void
	{
        $player = $transaction->getPlayer();
        $itemClicked = $transaction->getItemClicked();
        $itemClickedWith = $transaction->getItemClickedWith();
        $action = $transaction->getAction();
        $invTransaction = $transaction->getTransaction();

		$id= $action->getSlot();
		$player->removeWindow($action->getInventory());
		$transaction->then(function (Player $player) use ($id, $itemClicked)
        {
            $this->buyItem($id+1, $player, $itemClicked);
        });
	}

    /**
     * @param $id
     * @param Player $player
     * @param Item $item
     */
	public function buyItem ($id, Player $player, Item $item)
	{
		if ($this->getShop()[$id]['Sell']['money'] === "true")
		{
			if (EconomyAPI::getInstance()->myMoney($player) >= $this->getShop()[$id]['Sell']['price'])
			{
				$form = new ModalForm(function ($player, $data) use ($item, $id)
				{
					if ($data == true)
					{
						if($this->getShop()[$id]['Item']['Lore'] != "")
						{
							$item->setLore(array($this->getShop()[$id]['Item']['Lore']));
						}
						$player->getInventory()->addItem($item);
						EconomyAPI::getInstance()->reduceMoney($player, $this->getShop()[$id]['Sell']['price']);
						$player->sendMessage($this->getStg()['msg']['buy-done']);
					}
				});
				$form->setContent($this->getStg()['confirm']['content']);
				$form->setButton1($this->getStg()['confirm']['button-1']);
				$form->setButton2($this->getStg()['confirm']['button-2']);
				$form->sendToPlayer($player);
			}
			else 
			{
				$player->sendMessage($this->getStg()['msg']['buy-fail']);
			}
		}
		if ($this->getShop()[$id]['Sell']['coin'] === "true")
		{
			if (CoinSystem::getInstance()->getCoin($player) >= $this->getShop()[$id]['Sell']['price'])
			{
				$form = new ModalForm(function ($player, $data) use ($item, $id)
				{
					if ($data == true)
					{
						if($this->getShop()[$id]['Item']['Lore'] != "")
						{
							$item->setLore(array($this->getShop()[$id]['Item']['Lore']));
						}
						$player->getInventory()->addItem($item);
						CoinSystem::getInstance()->reduceCoin($player, $this->getShop()[$id]['Sell']['price']);
						$player->sendMessage($this->getStg()['msg']['buy-done']);
					}
				});
				$form->setContent($this->getStg()['confirm']['content']);
				$form->setButton1($this->getStg()['confirm']['button-1']);
				$form->setButton2($this->getStg()['confirm']['button-2']);
				$form->sendToPlayer($player);
			}
			else 
			{
				$player->sendMessage($this->getStg()['msg']['buy-fail']);
			}
		}
	}
}