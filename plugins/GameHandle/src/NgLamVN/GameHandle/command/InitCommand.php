<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;

class InitCommand
{
    public function __construct(Core $plugin)
    {
        $cmd = $plugin->getServer()->getCommandMap();

        $cmd->register("heal", new Heal($plugin));
        $cmd->register("feed", new Feed($plugin));
        $cmd->register("fly", new Fly($plugin));
        $cmd->register("gm1", new Gm1($plugin));
        $cmd->register("gm0", new Gm0($plugin));
        $cmd->register("gm2", new Gm2($plugin));
        $cmd->register("gm3", new Gm3($plugin));
        $cmd->register("sell", new Sell($plugin));
        $cmd->register("tpall", new TpAll($plugin));
        $cmd->register("fiversion", new FiVersion($plugin));
        $cmd->register("sudo", new Sudo($plugin));
        $cmd->register("smartmine", new SmartMine($plugin));
        $cmd->register("dupe", new Dupe($plugin));
        $cmd->register("reloadskin", new ReloadSkin($plugin));
        $cmd->register("tutorial", new Tutorial($plugin));
        $cmd->register("servercheck", new ServerCheck($plugin));
        $cmd->register("mute", new Mute($plugin));
        $cmd->register("unmute", new UnMute($plugin));
        $cmd->register("freeze", new Freeze($plugin));
        $cmd->register("unfreeze", new UnFreeze($plugin));
        $cmd->register("notp", new NoTP($plugin));
        $cmd->register("icgive", new IcGive($plugin));
    }
}
