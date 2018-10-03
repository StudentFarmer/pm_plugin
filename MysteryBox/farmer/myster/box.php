<?php
namespace farmer\myster;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;

class box extends PluginBase implements Listener{



public function onLoad(){
$this->getLogger()->info("랜덤박스 플러그인을 로드합니다.");
}
public function onEnable(){
$this->getLogger()->info("랜덤박스 플러그인을 성공적으로 로드하였습니다.");
$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
$this->economy = $this->getServer ()->getPluginManager ()->getPlugin ( "EconomyAPI" );
}

public function PlayerInteract(PlayerInteractEvent $event){
$player = $event->getPlayer();
$inventory = $player->getInventory ();

if($event->getBlock ()->getId () == 46 && $event->getBlock ()->getDamage () == 0) {
if(!$inventory->contains(Item::get(399, 0, 1))) {
$player->sendMessage("§l§f[ §a팜 코인 §f] 팜코인이 없어서 뽑기에 참여하실수가 없습니다.");

return;
}
else {
$inventory()->removeItem(new Item(399, 0, 1));
switch(mt_rand(0,1)){

case 0:
// 칸을 하나 열고
$player->sendMessage("§l§f[ §a팜 코인 §f] 아쉽게도 당첨되지 않았습니다.");
break;

case 1:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 5만원에 당첨되셨습니다.");
// $inventory->addItem ( Item::get ( 0, 0, 0 ) ) ); 
$this->economy->addMoney( $player, 50000 );
break;

case 2:
$player->sendMessage("§l§f[ §a팜 코인 §f] 꽁꽁얼은 얼음을 획득하였습니다.");
$inventory->addItem ( Item::get ( 174, 0, 1 ) ) ); 
break;

case 3:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 만원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 10000 );
break;

case 4:
$player->sendMessage("§l§f[ §a팜 코인 §f] 유기농으로 제작된 맥주를 획득하였습니다.");
$inventory->addItem ( Item::get ( 319, 0, 1 ) ) );
break;

case 5:
$player->sendMessage("§l§f[ §a팜 코인 §f] 황금사과를 획득 하였습니다!");
$inventory->addItem ( Item::get ( 322, 0, 1 ) ) );
break;

case 6:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 500원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 500 );
break;

case 7:
$player->sendMessage("§l§f[ §a팜 코인 §f] 달걀 3개를 획득 하였습니다.");
$inventory->addItem ( Item::get ( 344, 0, 3 ) ) );
break;

case 8:
$player->sendMessage("§l§f[ §a팜 코인 §f] 이럴수가!! 찢어진 드래곤의 날개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 444, 0, 1 ) ) ); 
break;

case 9:
$player->sendMessage("§l§f[ §a팜 코인 §f] 동물의 뼈를 획득하였습니다.");
$inventory->addItem ( Item::get ( 352, 0, 1 ) ) );
break;

case 10:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 1000원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 1000 );
break;

case 11:
$player->sendMessage("§l§f[ §a팜 코인 §f] 독이 있는 감자 5개 를 획득하였습니다.");
$inventory->addItem ( Item::get ( 394, 0, 5 ) ) );
break;

case 12:
$player->sendMessage("§l§f[ §a팜 코인 §f] 황금당근 1개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 396, 0, 1 ) ) );
break;

case 13:
$player->sendMessage("§l§f[ §a팜 코인 §f] 호박 파이 20개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 400, 0, 20 ) ) );
break;

case 14:
$player->sendMessage("§l§f[ §a팜 코인 §f] 익히지 않은 양고기 5개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 423, 0, 5 ) ) ); 
break;

case 15:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 10만원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 100000 );
break;

case 16:
$player->sendMessage("§l§f[ §a팜 코인 §f] 빨간 침대 를 획득하였습니다.");
$inventory->addItem ( Item::get ( 355, 0, 1 ) ) );
break;

case 17:
$player->sendMessage("§l§f[ §a팜 코인 §f] 뼈가루 64개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 351, 15, 64 ) ) );
break;

case 18:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 1800원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 1800 );
break;

case 19:
$player->sendMessage("§l§f[ §a팜 코인 §f] 쿠키 10개 를 획득하였습니다.");
$inventory->addItem ( Item::get ( 357, 0, 10 ) ) );
break;

case 20:
$player->sendMessage("§l§f[ §a팜 코인 §f] 팜 코인 2개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 399, 0, 2 ) ) );
break;

case 21:
$player->sendMessage("§l§f[ §a팜 코인 §f] 씨앗 10 개를 획득하였습니다.");
$inventory->addItem ( Item::get ( 295, 0, 10 ) ) );
break;

case 22:
$player->sendMessage("§l§f[ §a팜 코인 §f] 게임머니 100원에 당첨되셨습니다.");
$this->economy->addMoney( $player, 100 );
break;

case 23:
$player->sendMessage("§l§f[ §a팜 코인 §f] 사과 1개 를 획득하였습니다.");
$inventory->addItem ( Item::get ( 260, 0, 1 ) ) );
break;

case 24:
$player->sendMessage("§l§f[ §a팜 코인 §f] 슬라임블럭 1개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 165, 0, 1 ) ) );
break;

case 25:
$player->sendMessage("§l§f[ §a팜 코인 §f] 덩불 3개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 106, 0, 3 ) ) );
break;

case 26:
$player->sendMessage("§l§f[ §a팜 코인 §f] 잭 오랜턴 1개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 91, 0, 1 ) ) );
break;
    
case 27:
$player->sendMessage("§l§f[ §a팜 코인 §f] 제우스의 실수로 떨어진 불 1개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 51, 0, 1 ) ) );
break;
    
case 28:
$player->sendMessage("§l§f[ §a팜 코인 §f] 다이아몬드 2개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 264, 0, 2 ) ) );
break;
    
case 29:
$player->sendMessage("§l§f[ §a팜 코인 §f] 다이아몬드 5개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 264, 0, 5 ) ) );
break;
    
case 30:
$player->sendMessage("§l§f[ §a팜 코인 §f] 잔디 1개에 당첨되었습니다.");
$inventory->addItem ( Item::get ( 2, 0, 2 ) ) );
break;
}
}
}
}
}

?>
