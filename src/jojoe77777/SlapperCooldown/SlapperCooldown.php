<?php

namespace jojoe77777\SlapperCooldown;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use slapper\events\SlapperHitEvent;

class SlapperCooldown extends PluginBase implements Listener {

	/** @var array */
	public $lastHit = [];
	/** @var array */
	private $cfg = [];

	public function onEnable() {
	    $this->saveDefaultConfig();
	    $this->cfg = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param SlapperHitEvent $ev
     */
	public function onSlapperHit(SlapperHitEvent $ev){
	    $name = $ev->getDamager()->getName();
	    if(!isset($this->lastHit[$name])){
	        $this->lastHit[$name] = microtime(true);
	        return;
        }
        if(($this->lastHit[$name] + $this->cfg["delay"]) > (microtime(true))){
            $ev->setCancelled();
            $ev->getDamager()->sendTip($this->cfg["message"]);
        } else {
            $this->lastHit[$name] = microtime(true);
        }
        return;
    }

    public function onPlayerQuit(PlayerQuitEvent $ev){
        unset($this->lastHit[$ev->getPlayer()->getName()]);
    }
}
