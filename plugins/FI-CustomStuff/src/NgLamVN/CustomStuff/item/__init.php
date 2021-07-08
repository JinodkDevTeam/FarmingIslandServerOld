<?php

namespace NgLamVN\CustomStuff\item;

use NgLamVN\CustomStuff\CustomStuff;

class __init
{
    public CustomStuff $core;

    public function __construct(CustomStuff $core)
    {
        $this->core = $core;
        $plmng = $this->core->getServer()->getPluginManager();

        $plmng->registerEvents(new Crook($core), $core);
        $plmng->registerEvents(new DiverHelmet($core), $core);
    }
}