<?php

namespace cpt;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener
{


    public function onEnable()
    {
        $this->getLogger()->info("§4Plugin cây phát tài đã được bật bởi");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        @mkdir($this->getDataFolder(), 0744, true);
        $this->n = new Config($this->getDataFolder() . "nuoc.yml", Config::YAML);
        $this->cc = new Config($this->getDataFolder() . "phanboncc.yml", Config::YAML);
        $this->tc = new Config($this->getDataFolder() . "phanbontc.yml", Config::YAML);
        $this->sc = new Config($this->getDataFolder() . "phanbonsc.yml", Config::YAML);
        $this->kc = new Config($this->getDataFolder() . "kimcuong.yml", Config::YAML);
        $this->sat = new Config($this->getDataFolder() . "sat.yml", Config::YAML);
        $this->vang = new Config($this->getDataFolder() . "vang.yml", Config::YAML);
        $this->da = new Config($this->getDataFolder() . "da.yml", Config::YAML);
        $this->cap = new Config($this->getDataFolder() . "cap.yml", Config::YAML);
        $this->kn = new Config($this->getDataFolder() . "kinhnghiem.yml", Config::YAML);
        $cfg = new Config($this->getDataFolder() . "player.yml", Config::YAML);
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        if (!$this->n->exists($ev->getPlayer()->getName())) {
            $this->n->set($ev->getPlayer()->getName(), 0);
            $this->n->save();
        }
        if (!$this->cc->exists($ev->getPlayer()->getName())) {
            $this->cc->set($ev->getPlayer()->getName(), 0);
            $this->cc->save();
        }
        if (!$this->sc->exists($ev->getPlayer()->getName())) {
            $this->sc->set($ev->getPlayer()->getName(), 0);
            $this->sc->save();
        }
        if (!$this->tc->exists($ev->getPlayer()->getName())) {
            $this->tc->set($ev->getPlayer()->getName(), 0);
            $this->tc->save();
        }
        if (!$this->kc->exists($ev->getPlayer()->getName())) {
            $this->kc->set($ev->getPlayer()->getName(), 0);
            $this->kc->save();
        }
        if (!$this->sat->exists($ev->getPlayer()->getName())) {
            $this->sat->set($ev->getPlayer()->getName(), 0);
            $this->sat->save();
        }
        if (!$this->vang->exists($ev->getPlayer()->getName())) {
            $this->vang->set($ev->getPlayer()->getName(), 0);
            $this->vang->save();
        }
        if (!$this->da->exists($ev->getPlayer()->getName())) {
            $this->da->set($ev->getPlayer()->getName(), 0);
            $this->da->save();
        }
        if (!$this->cap->exists($ev->getPlayer()->getName())) {
            $this->cap->set($ev->getPlayer()->getName(), 1);
            $this->cap->save();
        }
        if (!$this->kn->exists($ev->getPlayer()->getName())) {
            $this->kn->set($ev->getPlayer()->getName(), 0);
            $this->kn->save();
        }
    }

    public function onQuit(PlayerQuitEvent $ev)
    {
        $this->kc->save();
        $this->sat->save();
        $this->vang->save();
        $this->da->save();
        $this->kn->save();
        $this->cap->save();
        $this->cc->save();
        $this->tc->save();
        $this->sc->save();
        $this->n->save();
    }

    public function kc(BlockBreakEvent $ev)
    {
        if ($ev->getBlock()->getId() == 56) {
            $this->kc->set($ev->getPlayer()->getName(), ($this->kc->get($ev->getPlayer()->getName()) + 1));
            $this->kc->save();
            $slkc = $this->kc->get($ev->getPlayer()->getName());
            if ($this->kc->get($ev->getPlayer()->getName()) == 100) {
                $this->kc->set($ev->getPlayer()->getName(), 0);
                $this->kc->save();
                $this->cc->set($ev->getPlayer()->getName(), ($this->cc->get($ev->getPlayer()->getName()) + 1));
                $this->cc->save();
            }
        }
    }

    public function vang(BlockBreakEvent $ev)
    {
        if ($ev->getBlock()->getId() == 14) {
            $this->vang->set($ev->getPlayer()->getName(), ($this->vang->get($ev->getPlayer()->getName()) + 1));
            $this->vang->save();
            if ($this->vang->get($ev->getPlayer()->getName()) == 75) {
                $this->vang->set($ev->getPlayer()->getName(), 0);
                $this->vang->save();
                $this->tc->set($ev->getPlayer()->getName(), ($this->tc->get($ev->getPlayer()->getName()) + 1));
                $this->tc->save();

            }
        }
    }

    public function da(BlockBreakEvent $ev)
    {
        if ($ev->getBlock()->getId() == 1) {
            $this->da->set($ev->getPlayer()->getName(), ($this->da->get($ev->getPlayer()->getName()) + 1));
            $this->da->save();
            if ($this->da->get($ev->getPlayer()->getName()) == 200) {
                $this->da->set($ev->getPlayer()->getName(), 0);
                $this->da->save();
                $this->n->set($ev->getPlayer()->getName(), ($this->n->get($ev->getPlayer()->getName()) + 1));
                $this->n->save();
            }
        }
    }


    public function sat(BlockBreakEvent $ev)
    {
        if ($ev->getBlock()->getId() == 15) {
            $this->sat->set($ev->getPlayer()->getName(), ($this->sat->get($ev->getPlayer()->getName()) + 1));
            $this->sat->save();
            if ($this->sat->get($ev->getPlayer()->getName()) == 50) {
                $this->sat->set($ev->getPlayer()->getName(), 0);
                $this->sat->save();
                $this->sc->set($ev->getPlayer()->getName(), ($this->sc->get($ev->getPlayer()->getName()) + 1));
                $this->sc->save();
            }
        }
    }

    public function lenhcap($sender)
    {
        $cap = $this->cap->get($sender->getPlayer()->getName());
        $lenhcap = $cap * 250;
        if ($this->kn->get($sender->getPlayer()->getName()) > $lenhcap) {
            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) - $lenhcap));
            $this->kn->save();
            $this->cap->set($sender->getPlayer()->getName(), ($this->cap->get($sender->getPlayer()->getName()) + 1));
            $sender->sendMessage("§l§b❖§c Bạn đã lênh cấp §3" . $cap . " §b ❖");
            $this->cap->save();
            $this->lenhcap($sender);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "cay":
                $this->menu($sender);
                return true;
        }
        return true;
    }

    public function menu($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
            }
            switch ($result) {
                case 0:
                    break;
                case 1:
                    $this->Toplevel($sender);
                    break;
                case 2:
                    $this->cay($sender);
                    break;
                case 3:
                    $this->nhiemvu($sender);
                    break;
                case 4:
                    $this->shop($sender);
                    break;
            }
        });
        $form->setTitle("§3§l§o§d cây phát tài");
        $form->addButton("§7》§c§lThoát§r§7《");
        $form->addButton("§2ミ§6★§c§o§ltop cây§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lXem cây§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lnhiệm vụ§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lshop§l§6★§2彡");
        $form->sendToPlayer($sender);
    }

    public function nhiemvu($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
            }
            switch ($result) {
                case 0:
                    $this->menu($sender);
                    break;
            }
        });
        $name = $sender->getPlayer()->getName();
        $sat = $this->sat->get($sender->getPlayer()->getName());
        $vang = $this->vang->get($sender->getPlayer()->getName());
        $kc = $this->kc->get($sender->getPlayer()->getName());
        $da = $this->da->get($sender->getPlayer()->getName());
        $form->setTitle("§7[ §c§l nhiệm §e vụ §r§7]");
        $form->setContent("§f§oSắt " . $sat . "§2/§f50 §6§o✓Nhận được phân bón sơ cấp\n§b§oVàng §6" . $vang . "§2/§f75 §6§o✓Nhận được phân bón trung cấp\n§b§oKim cương §b" . $kc . "§2/§f100 §6§o✓Nhận được phân bón cao cấp\n§8§oĐá§f " . $da . "§2/§f200 §6§o✓Nhận được nước\n §c§l§oNhững nhiệm vụ nầy sẽ lập lại vô hạn ");
        $form->addButton("§7》§c§lQuay lại§r§7《");
        $form->sendToPlayer($sender);
    }

    public function shop($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
            }
            switch ($result) {
                case 0:
                    $this->menu($sender);
                    break;
                case 1:
                    $name = $sender->getName();
                    $mymoney = $this->eco->myMoney($sender);
                    if ($mymoney < 50000) {
                        $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
                    } else {
                        $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón cao cấp  với giá 50.000 xu Thành Công §b❖");
                        $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) + 1));
                        $this->cc->save();
                        $this->eco->reduceMoney($name, 50000);
                    }
                    break;
                case 2:
                    $name = $sender->getName();
                    $mymoney = $this->eco->myMoney($sender);
                    if ($mymoney < 35000) {
                        $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
                    } else {
                        $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón cao cấp với giá 35.000 xu Thành Công §b❖");
                        $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) + 1));
                        $this->tc->save();
                        $this->eco->reduceMoney($name, 35000);
                    }
                    break;
                case 3:
                    $name = $sender->getName();
                    $mymoney = $this->eco->myMoney($sender);
                    if ($mymoney < 15000) {
                        $sender->sendMessage("§l§b❖§c Bạn không đủ xu để mua phân bón cao cấp §b ❖");
                    } else {
                        $sender->sendMessage("§l§b❖§a Bạn Đã Mua phân bón sơ cấp với giá 15.000 xu Thành Công §b❖");
                        $this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) + 1));
                        $this->sc->save();
                        $this->eco->reduceMoney($name, 15000);
                    }
                    break;

            }
        });
        $mymoney = $this->eco->myMoney($sender);
        $name = $sender->getPlayer()->getName();
        $cc = $this->cc->get($sender->getPlayer()->getName());
        $sc = $this->sc->get($sender->getPlayer()->getName());
        $tc = $this->tc->get($sender->getPlayer()->getName());
        $n = $this->n->get($sender->getPlayer()->getName());
        $form->setTitle("§7[ §c§l shop §r§7]");
        $form->setContent("§f§a§oshop bán phân bón\n§6§o§lSố tiền của bạn: §r§f" . $mymoney);
        $form->addButton("§7》§c§lQuay lại§r§7《");
        $form->addButton("§2ミ§6★§c§o§lPhân bón cao cấp§l§6★§2彡\n §2§l§oGiá 50k x1");
        $form->addButton("§2ミ§6★§c§o§lPhân bón trung cấp§l§6★§2彡\n 2§l§oGiá 50k x1");
        $form->addButton("§2ミ§6★§c§o§lPhân bón sơ cấp§l§6★§2彡\n 2§l§oGiá 50k x1");
        $form->sendToPlayer($sender);
    }

    public function cay($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
            }
            switch ($result) {
                case 0:
                    $this->menu($sender);
                    break;
                case 1:
                    $this->phanbon($sender);
                    break;

            }
        });
        $name = $sender->getPlayer()->getName();
        $cc = $this->cc->get($sender->getPlayer()->getName());
        $sc = $this->sc->get($sender->getPlayer()->getName());
        $tc = $this->tc->get($sender->getPlayer()->getName());
        $n = $this->n->get($sender->getPlayer()->getName());
        $cap = $this->cap->get($sender->getPlayer()->getName());
        $kn = $this->kn->get($sender->getPlayer()->getName());
        $maxkn = $cap * 250;
        $form->setTitle("§7[ §c§l cây §e phát tài §r§7]");
        $form->setContent("§2§l§o§mTên người chơi: §r§o§l§d" . $name . "\n§2§l§o§mcấp: §r§o§l" . $cap . "\n§2§l§o§mkinh nghiệm: §r§o§l" . $kn . "§6§l/" . $maxkn . "\n--------------------------------\n§2§l§o§mPhân Bón\n§f-§2§l§o§mphân bón sơ cấp: §r§o§l" . $sc . "\n§f-§2§l§o§mphân bón trung cấp: §r§o§l" . $tc . "\n§f-§2§l§o§mphân bón cao cấp: §r§o§l" . $cc . "\n§f-§2§l§o§mnước: §r§o§l" . $n . "\n sử dụng phân bón và nước để tăng cấp cây phát tài");
        $form->addButton("§7》§c§lQuay lại§r§7《");
        $form->addButton("§2ミ§6★§c§o§lbón phân§6★§2彡\n§2ミ§6★§c§o§ltưới nước§6★§2彡");
        $form->sendToPlayer($sender);
    }

    public function phanbon($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
            }
            switch ($result) {
                case 0:
                    break;
                case 1:
                    $this->phanbonsocap($sender);
                    break;
                case 2:
                    $this->phanbontrungcap($sender);
                    break;
                case 3:
                    $this->phanboncaocap($sender);
                    break;
                case 4:
                    $this->nuoc($sender);
                    break;

            }
        });
        $form->setTitle("§3§l§o§d cây phát tài");
        $form->addButton("§7》§c§lThoát§r§7《");
        $form->addButton("§2ミ§6★§c§o§lphân bón sơ cấp§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lphân bón trung cấp§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lphân bón cao cấp§6★§2彡");
        $form->addButton("§2ミ§6★§c§o§lnước§l§6★§2彡");
        $form->sendToPlayer($sender);
    }

    public function phanbonsocap($sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $sender, $data) {
            if (!is_null($data)) {
                switch ($data[1]) {
                    case 0:
                        $sc = $this->sc->get($sender->getPlayer()->getName());
                        if ($sc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón để bón §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân Thành Công §b❖");
                            $this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) - 1));
                            $this->sc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 20));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 1:
                        $sc = $this->sc->get($sender->getPlayer()->getName());
                        if ($sc < 2) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón sơ cấp để bón phân x2 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân sơ cấp x2Thành Công §b❖");
                            $this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) - 2));
                            $this->sc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 40));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 2:
                        $sc = $this->sc->get($sender->getPlayer()->getName());
                        if ($sc < 3) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón sơ cấp để bón phân x3 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân sơ cấp x3 Thành Công §b❖");
                            $this->sc->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) - 3));
                            $this->sc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 60));
                            $this->kn->save();
                        }
                        break;
                    case 3:
                        $sc = $this->sc->get($sender->getPlayer()->getName());
                        if ($sc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không còn phân bón §b ❖");
                        } else {
                            $sc = $this->sc->get($sender->getPlayer()->getName());
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân sơ x" . $sc . " thành công §b❖");
                            $this->sc->set($sender->getPlayer()->getName(), ($this->sc->get($sender->getPlayer()->getName()) - $sc));
                            $this->sc->save();
                            $sckn = $sc * 20;
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $sckn));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                        return;
                }
            }

        });
        $sc = $this->sc->get($sender->getPlayer()->getName());
        $form->setTitle("§l§6bón phân sơ cấp");
        $form->addLabel("§o§l§msố lượng phân bón sơ cấp: §r§f" . $sc);
        $form->addDropdown("Cấp", ["§b§lphân bón x1", "§b§lphân bón x2", "§b§lphân bón x3", "§b§lsử dụng hết"]);
        $form->sendToPlayer($sender);
    }

    public function phanboncaocap($sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $sender, $data) {
            if (!is_null($data)) {
                switch ($data[1]) {
                    case 0:
                        $cc = $this->Cc->get($sender->getPlayer()->getName());
                        if ($cc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón để bón §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân Thành Công §b❖");
                            $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) - 1));
                            $this->cc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 20));
                            $this->kn->save();
                        }
                        break;
                    case 1:
                        $cc = $this->cc->get($sender->getPlayer()->getName());
                        if ($cc < 2) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón cao cấp để bón phân x2 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân cao cấp x2Thành Công §b❖");
                            $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) - 2));
                            $this->cc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 100));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 2:
                        $cc = $this->cc->get($sender->getPlayer()->getName());
                        if ($cc < 3) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón cao cấp để bón phân x3 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân cao cấp x3 Thành Công §b❖");
                            $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) - 3));
                            $this->cc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 150));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 3:
                        $cc = $this->cc->get($sender->getPlayer()->getName());
                        if ($cc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không còn phân bón §b ❖");
                        } else {
                            $cc = $this->cc->get($sender->getPlayer()->getName());
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân cao cấp x" . $cc . " thành công §b❖");
                            $cckn = $cc * 50;
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $cckn));
                            $this->kn->save();
                            $this->cc->set($sender->getPlayer()->getName(), ($this->cc->get($sender->getPlayer()->getName()) - $cc));
                            $this->sc->save();
                            $this->lenhcap($sender);
                        }
                        break;
                        return;
                }
            }

        });
        $cc = $this->cc->get($sender->getPlayer()->getName());
        $form->setTitle("§l§6bón phân cao cấp");
        $form->addLabel("§o§l§msố lượng phân bón cao cấp: §r§f" . $cc);
        $form->addDropdown("Cấp", ["§b§lphân bón x1", "§b§lphân bón x2", "§b§lphân bón x3", "§b§lsử dụng hết"]);
        $form->sendToPlayer($sender);
    }

    public function phanbontrungcap($sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $sender, $data) {
            if (!is_null($data)) {
                switch ($data[1]) {
                    case 0:
                        $tc = $this->tc->get($sender->getPlayer()->getName());
                        if ($tc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón để bón §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân Thành Công §b❖");
                            $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) - 1));
                            $this->tc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 35));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 1:
                        $tc = $this->tc->get($sender->getPlayer()->getName());
                        if ($tc < 2) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón trung cấp để bón phân x2 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân trung cấp x2Thành Công §b❖");
                            $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) - 2));
                            $this->tc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 70));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 2:
                        $sc = $this->sc->get($sender->getPlayer()->getName());
                        if ($sc < 3) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ phân bón trung cấp để bón phân x3 §b ❖");
                        } else {
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân trung cấp x3 Thành Công §b❖");
                            $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) - 3));
                            $this->tc->save();
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + 105));
                            $this->kn->save();
                            $this->lenhcap($sender);
                        }
                        break;
                    case 3:
                        $tc = $this->tc->get($sender->getPlayer()->getName());
                        if ($tc < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không còn phân bón §b ❖");
                        } else {
                            $tc = $this->tc->get($sender->getPlayer()->getName());
                            $sender->sendMessage("§l§b❖§a Bạn Đã bón phân cao cấp x" . $tc . " thành công §b❖");
                            $tckn = $tc * 35;
                            $this->kn->set($sender->getPlayer()->getName(), ($this->kn->get($sender->getPlayer()->getName()) + $tckn));
                            $this->kn->save();
                            $this->tc->set($sender->getPlayer()->getName(), ($this->tc->get($sender->getPlayer()->getName()) - $tc));
                            $this->tc->save();
                            $this->lenhcap($sender);
                        }
                        break;
                        return;
                }
            }

        });
        $tc = $this->tc->get($sender->getPlayer()->getName());
        $form->setTitle("§l§6bón phân trung cấp");
        $form->addLabel("§o§l§msố lượng phân bón sơ cấp: §r§f" . $tc);
        $form->addDropdown("Cấp", ["§b§lphân bón x1", "§b§lphân bón x2", "§b§lphân bón x3", "§b§lsử dụng hết"]);
        $form->sendToPlayer($sender);
    }

    public function nuoc($sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $sender, $data) {
            if (!is_null($data)) {
                switch ($data[1]) {
                    case 0:
                        $n = $this->n->get($sender->getPlayer()->getName());
                        if ($n < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ nước để tưới cây§b ❖");
                        } else {
                            $cap = $this->cap->get($sender->getPlayer()->getName());
                            $tien = $cap * 1000;
                            $this->eco->addMoney($sender->getPlayer()->getName(), $tien);
                            $sender->sendMessage("§l§b❖§a Bạn Đã tưới cây thành công và nhận " . $tien . " xu §b❖");
                            $this->n->set($sender->getPlayer()->getName(), ($this->n->get($sender->getPlayer()->getName()) - 1));
                            $this->n->save();
                        }
                        break;
                    case 1:
                        $n = $this->n->get($sender->getPlayer()->getName());
                        if ($n < 2) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ nước x2 để tưới §b ❖");
                        } else {
                            $cap = $this->cap->get($sender->getPlayer()->getName());
                            $tien = $cap * 2000;
                            $this->eco->addMoney($sender->getPlayer()->getName(), $tien);
                            $sender->sendMessage("§l§b❖§a Bạn Đã tưới cây x2thành công và nhận " . $tien . " xu §b❖");
                            $this->n->set($sender->getPlayer()->getName(), ($this->n->get($sender->getPlayer()->getName()) - 2));
                            $this->n->save();
                        }
                        break;
                    case 2:
                        $n = $this->n->get($sender->getPlayer()->getName());
                        if ($n < 3) {
                            $sender->sendMessage("§l§b❖§c Bạn không đủ nước để tưới cây §b ❖");
                        } else {
                            $cap = $this->cap->get($sender->getPlayer()->getName());
                            $tien = $cap * 3000;
                            $this->eco->addMoney($sender->getPlayer()->getName(), $tien);
                            $sender->sendMessage("§l§b❖§a Bạn Đã tưới cây x3 thành công và nhận " . $tien . " xu §b❖");
                            $this->n->set($sender->getPlayer()->getName(), ($this->n->get($sender->getPlayer()->getName()) - 3));
                            $this->n->save();
                        }
                        break;
                    case 3:
                        $n = $this->n->get($sender->getPlayer()->getName());
                        if ($n < 1) {
                            $sender->sendMessage("§l§b❖§c Bạn không còn nước §b ❖");
                        } else {
                            $n = $this->n->get($sender->getPlayer()->getName());
                            $cap = $this->cap->get($sender->getPlayer()->getName());
                            $tien1 = $n * 1000;
                            $tien = $cap * $tien1;
                            $this->eco->addMoney($sender->getPlayer()->getName(), $tien);
                            $sender->sendMessage("§l§b❖§a Bạn Đã tưới cây x" . $tien . " thành công §b❖");
                            $this->n->set($sender->getPlayer()->getName(), ($this->n->get($sender->getPlayer()->getName()) - $n));
                            $this->n->save();
                        }
                        break;
                        return;
                }
            }

        });
        $n = $this->n->get($sender->getPlayer()->getName());
        $form->setTitle("§l§6 tưới nước");
        $form->addLabel("§o§l§msố lượng nước: §r§f" . $n);
        $form->addDropdown("Cấp", ["§b§l nước x1", "§b§lnước x2", "§b§lnước x3", "§b§ltưới hết"]);
        $form->sendToPlayer($sender);
    }

    public function Toplevel(Player $sender)
    {
        $levelplot = $this->cap->getAll();
        $message = "";
        $message1 = "";
        if (count($levelplot) > 0) {
            arsort($levelplot);
            $i = 1;
            foreach ($levelplot as $name => $level) {
                $message .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Cấp\n\n";
                $message1 .= "§l§3TOP " . $i . ": §6" . $name . " §d→ §f" . $level . " §2Cấp\n";
                if ($i >= 10) {
                    break;
                }
                ++$i;
            }
        }

        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null) {
            $result = $data;
            switch ($result) {
                case 0:
                    $this->Menu($sender);
                    break;
            }
        });
        $form->setTitle("§l§6¤¤§2Top Cây Phát Tài §6¤¤");
        $form->setContent($message);
        $form->addButton("§l§2° §4Quay Lại §e §2°");
        $form->sendToPlayer($sender);
        return $form;
    }
}















