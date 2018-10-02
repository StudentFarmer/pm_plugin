<?php

namespace water;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\player\PlayerItemHeldEvent;

class item extends PluginBase implements Listener{
 
 public function onEnable() {
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        
public function Main($i){
if(($i->getItem()->getID()===351,6)){
 $ef = new EffectInstance(Effect::getEffect(Effect::WATER_BREATHING), self::MAX_DURATION, 0);
				
                $player->addEffect($ef);
            } else {
                $player->removeEffect(Effect::WATER_BREATHINGSSS);
            }
            }
