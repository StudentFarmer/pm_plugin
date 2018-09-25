<?php

namespace save;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;

class Main extends PluginBase implements Listener{

    public $drops = array();

    public function onEnable(){
        $this->getServer()->getLogger()->info(TextFormat::LIGHT_PURPLE."그렇다.. 이것은 파머가 인벤세이브 플러그인을 찾던도중 우연히 소스코드를 얻어 만들어진 플러그인이다. 정상작동만 잘되면 되는거 아닌가!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }


   public function PlayerDeath(PlayerDeathEvent $event){
    $event->setKeepInventory(true);
}
    
}
