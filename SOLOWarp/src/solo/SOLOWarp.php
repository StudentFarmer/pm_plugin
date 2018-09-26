<?php

namespace solo;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class SOLOWarp extends PluginBase implements Listener {

  public $config;
  public $configData;

  public $warpConfig;
  public $warpData;

  public $gateConfig;
  public $gateData;

  public $tagblock = null;

  public function onEnable() {
     @mkdir ( $this->getDataFolder () );
		
		$this->config = new Config ( $this->getDataFolder () . "setting.yml", Config::YAML, [] );
		$this->configData = $this->config->getAll ();

		$this->warpConfig = new Config ( $this->getDataFolder () . "warp.yml", Config::YAML );
		$this->warpData = $this->warpConfig->getAll ();

		$this->gateConfig = new Config ( $this->getDataFolder () . "gate.yml", Config::YAML );
		$this->gateData = $this->gateConfig->getAll ();

       $this->tagblock = $this->getServer()->getPluginManager()->getPlugin("TAGBlock");

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
  }


	public function onDisable() {
		$this->warpConfig->setAll ( $this->warpData );
		$this->warpConfig->save();
		$this->gateConfig->setAll ( $this->gateData );
		$this->gateConfig->save();
	}

  public function getPos(Player $player) {
    $ret = [];
    $ret['x'] = ((floor($player->getX()*10))/10);
    $ret['y'] = ((floor($player->getY()*10))/10) ;
    $ret['z'] = ((floor($player->getZ()*10))/10) ;
    $ret['level'] = $player->getLevel()->getName();
    return $ret;
  }

  public function addTag($x, $y, $z, Level $level, $msg){
    if($this->tagblock == null) return;
    $this->tagblock->addTag(new Position($x, $y, $z, $level), $msg);
  }

  public function addInstanceTag($x, $y, $z, Level $level, $msg, $tick){
    if($this->tagblock == null) return;
    $this->tagblock->addInstanceTag(new Position($x, $y, $z, $level), $msg, $tick);
  }

  public function delTag($x, $y, $z, Level $level){
    if($this->tagblock == null) return;
    $this->tagblock->deleteTag(new Position($x, $y, $z, $level));
  }

  public function ExecuteWarp(Player $player, $w) {
    if(!$player instanceof Player) {
      $player->sendMessage("§b§o[ 알림 ] §7인게임 에서만 가능합니다.");
      return true;
    }
    if(!$this->isExistWarp($w)) {
      $player->sendMessage("§b§o[ 알림 ] §7해당 워프는 존재하지 않습니다.");
      return true;
    }
    $warp = explode('/', $this->getWarp($w));
    $pos = explode(':', $warp[0]);
    $this->addInstanceTag($player->getX(), ($player->getY()+1.5), $player->getZ(), $player->getLevel(), "§f> §d".implode(' ', $w)." §f<", 30);
    $player->teleport(new Position ( $pos[0], $pos[1], $pos[2], $this->getServer()->getLevelByName($warp[1])), $player->getYaw(), $player->getPitch());
    $player->sendMessage("§b§o[ 알림 ] §7성공적으로 워프하셨습니다.");
  }

  public function sendHelp(CommandSender $player) {
    if($player->isOp()) {
      $player->sendMessage("§b§o[ 알림 ] §7 ====== 워프 명령어 목록 ======");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 [워프 이름] <하위 워프> <하위 워프> - 해당 워프로 이동합니다.");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 생성 [워프 이름] <하위 워프> <하위 워프> - 워프를 생성합니다.");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 제거 [워프 이름] <하위 워프> <하위 워프> - 워프를 제거합니다.");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 목록 [워프 이름] <하위 워프> - 워프목록을 표시합니다.");
    } else {
      $player->sendMessage("§b§o[ 알림 ] §7 ====== 워프 명령어 목록 ======");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 [워프 이름] - 해당 워프로 이동합니다.");
      $player->sendMessage("§b§o[ 알림 ] §7/워프 목록 - 워프목록을 표시합니다.");
    }
  }

  public function makeW($string) {
    $string = explode(' ', $string);
    return $string;
  }

  public function getWarp($w) {
    $c = count($w);
    if($c == 1)
      return $this->warpData[$w[0]]['DATA::POS'];
    else if($c == 2)
      return $this->warpData[$w[0]][$w[1]]['DATA::POS'];
    else if($c == 3)
      return $this->warpData[$w[0]][$w[1]][$w[2]]['DATA::POS'];
  }

  public function isExistWarp($w) {
    $c = count($w);
    if($c == 1) {
      if(isset($this->warpData[$w[0]]['DATA::POS']))
        return true;
      else
        return false;
    }
    else if($c == 2) {
      if(isset($this->warpData[$w[0]][$w[1]]['DATA::POS']))
        return true;
      else
        return false;
    }
    else if($c == 3) {
      if(isset($this->warpData[$w[0]][$w[1]][$w[2]]['DATA::POS']))
        return true;
      else
        return false;
    }
    return;
  }

  //return boolean
  public function hasBelowWarp($w) {
    $c = count($w);
    if($c == 1)
      if(count($this->warpData[$w[0]]) >= 2)
        return true;
      else
        return false;
    else if($c == 2)
      if(count($this->warpData[$w[0]][$w[1]]) >= 2)
        return true;
      else
        return false;
    return false;
  }

  public function writeWarpData($w, $pos) {
    $c = count($w);
    if($c == 1) {
      if(!$this->isExistWarp($w))
        $this->warpData[$w[0]] = [];
      $this->warpData[$w[0]]['DATA::POS'] = $pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level'];
      return;
    }
    else if($c == 2) {
      if(!$this->isExistWarp($w))
        $this->warpData[$w[0]][$w[1]] = [];
      $this->warpData[$w[0]][$w[1]]['DATA::POS'] = $pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level'];
      return;
    }
    else if($c == 3) {
      if(!$this->isExistWarp($w))
        $this->warpData[$w[0]][$w[1]][$w[2]] = [];
      $this->warpData[$w[0]][$w[1]][$w[2]]['DATA::POS'] = $pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level'];
      return;
    }
    return;
  }

  public function delWarpData($w) {
    $c = count($w);
    if($c == 1) {
      unset($this->warpData[$w[0]]);
      return;
    }
    else if($c == 2) {
      unset($this->warpData[$w[0]][$w[1]]);
      return;
    }
    else if($c == 3) {
      unset($this->warpData[$w[0]][$w[1]][$w[2]]);
      return;
    }
  }

  public function writeGateData($w, $pos) {
    $c = count($w);
    if($c == 1) {
      $this->gateData[$pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level']] = $w[0];
       return;
    }
    else if($c == 2) {
      $this->gateData[$pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level']] = $w[0]." ".$w[1];
       return;
    }
    else if($c == 3) {
      $this->gateData[$pos['x'].':'.$pos['y'].':'.$pos['z'].'/'.$pos['level']] = $w[0]." ".$w[1]." ".$w[2];
       return;
    }
  }

  public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
    $msg = explode(' ', substr($event->getMessage(), 1));
    if(!$this->isExistWarp($msg)) return;
    $this->ExecuteWarp($event->getPlayer(), $msg);
    $event->setCancelled();
  }

	public function onCommand(CommandSender $sender, Command $command, $label, array $args):bool {
    if($command->getName() == "워프"||strtolower($command->getName()) == "warp") {
      if(!isset($args[0])) {
        $this->sendHelp($sender);
        return true;
      }
      switch($args[0]) {
        case "생성":
        case "추가":
        if(!$sender instanceof Player) {
          $sender->sendMessage("§b§o[ 알림 ] §7인게임 내에서만 가능합니다.");
          return true;
        }
        if(!$sender->isOp()) {
          $sender->sendMessage("§b§o[ 알림 ] §7권한이 없습니다.");
          return true;
        }
        if(!isset($args[1])) {
          $sender->sendMessage("§b§o[ 알림 ] §7사용법 : /".$command->getName()." ".$args[0]." "."[워프 이름] <하위 워프> <하위 워프>");
          return true;
        } else {
          switch($args[1]) {
            case "생성":
            case "제거":
            case "추가":
            case "삭제":
            case "목록":
            case "DATA::POS":
            $sender->sendMessage("§b§o[ 알림 ] §7그 이름으로 워프를 생성할 수 없습니다.");
            return true;
            default:
            break;
          }
          $w = [];
          $w[0] = $args[1];
          if(isset($args[2])) {
            $w[1] = $args[2];
            if(isset($args[3])) {
              $w[2] = $args[3];
              if(isset($args[4])) {
                $sender->sendMessage("§b§o[ 알림 ] §7하위 워프는 최대 2개까지 생성 가능합니다.");
                return true;
              }
            }
          }
          if($this->isExistWarp($w)) {
            $sender->sendMessage("§b§o[ 알림 ] §7이미 존재하는 워프 이름입니다.");
            return true;
          }
          if(count($w) == 2) {
            $tmp = $this->makeW($w[0]);
            if(!$this->isExistWarp($tmp)) {
              $sender->sendMessage("§b§o[ 알림 ] §7“".$w[0]."” 이름의 상위 워프는 존재하지 않습니다.");
              return true;
            }
          }
          if(count($w) == 3) {
            $tmp = $this->makeW($w[0]." ".$w[1]);
            if(!$this->isExistWarp($tmp)) {
              $sender->sendMessage("§b§o[ 알림 ] §7“".$w[0]." ".$w[1]."” 이름의 상위 워프는 존재하지 않습니다.");
              return true;
            }
          }
          $this->writeWarpData($w, $this->getPos($sender));
          $sender->sendMessage("§b§o[ 알림 ] §7성공적으로 워프를 생성하였습니다.");
          return true;
        }
        return true;

        case "제거":
        case "삭제":
        if(!$sender->isOp()) {
          $sender->sendMessage("§b§o[ 알림 ] §7권한이 없습니다.");
          return true;
        }
        if(!isset($args[1])) {
          $sender->sendMessage("§b§o[ 알림 ] §7사용법 : /".$command->getName()." ".$args[0]." "."[워프 이름] <하위 워프> <하위 워프>");
          return true;
        } else {
          $w = [];
          $w[0] = $args[1];
          if(isset($args[2])) {
            $w[1] = $args[2];
            if(isset($args[3]))
              $w[2] = $args[3];
          }
          if(!$this->isExistWarp($w)) {
            $sender->sendMessage("§b§o[ 알림 ] §7해당 워프는 존재하지 않습니다.");
            return true;
          }
          $this->delWarpData($w);
          if(!$this->isExistWarp($w)) {
            $sender->sendMessage("§b§o[ 알림 ] §7성공적으로 워프를 제거하였습니다.");
            return true;
          } else {
            $sender->sendMessage("§b§o[ 알림 ] §7워프 제거에 실패하였습니다. 이 오류가 계속 나타난다면 플러그인 개발자에게 오류를 제보해주세요.");
            return true;
          }
        }
        return true;

        case "목록":
        if(!isset($args[1])) {
          $sender->sendMessage("§b§o[ 알림 ] §7워프 목록을 표시합니다.");
          $output = "";
          foreach(array_keys($this->warpData) as $key) {
            if(($c = count($this->warpData[$key])) >= 2) $i = "(하위 워프 : ".($c-1)."개)"; else $i = "";
            $output .= "<§a".$key."§7".$i."§f> ";
          }
          $sender->sendMessage("워프 목록 : ".$output);
          return true;
        } else {
          $w = [];
          $w[0] = $args[1];
          if(!$this->isExistWarp($w)) {
            $sender->sendMessage("§b§o[ 알림 ] §7해당 워프는 존재하지 않습니다.");
            return true;
          }
          if(!$this->hasBelowWarp($w)) {
            $sender->sendMessage("§b§o[ 알림 ] §7해당 워프는 하위 워프가 없습니다.");
            return true;
          }
          if(!isset($args[2])) {
            $sender->sendMessage("§b§o[ 알림 ] §7워프 목록을 표시합니다.");
            $output = "";
            foreach(array_keys($this->warpData[$args[1]]) as $key) {
              if($key == "DATA::POS") continue;
              if(($c = count($this->warpData[$args[1]][$key])) >= 2) $i = "(하위 워프 : ".($c-1)."개)"; else $i = "";
              $output .= "<§a".$key."§7".$i."§f> ";
            }
            $sender->sendMessage("§d".$args[1]." §f하위 워프 목록 : ".$output);
            return true;
          } else if(!isset($args[3])) {

            $w = [];
            $w[0] = $args[1];
            $w[1] = $args[2];
            if(!$this->isExistWarp($w)) {
              $sender->sendMessage("§b§o[ 알림 ] §7해당 워프는 존재하지 않습니다.");
              return true;
            }
            if(!$this->hasBelowWarp($w)) {
              $sender->sendMessage("§b§o[ 알림 ] §7해당 워프는 하위 워프가 없습니다.");
              return true;
            }

            $sender->sendMessage("§b§o[ 알림 ] §7워프 목록을 표시합니다.");
            $output = "";
            foreach(array_keys($this->warpData[$args[1]][$args[2]]) as $key) {
              if($key == "DATA::POS") continue;
              $output .= "<§a".$key."§f> ";
            }
            $sender->sendMessage("§d".$args[1]." ".$args[2]." §f하위 워프 목록 : ".$output);
            return true;
          } else {
            $sender->sendMessage("§b§o[ 알림 ] §7사용법 : /워프 목록 [워프 이름] <하위 워프>");
            return true;
          }
        }
        default:
          $w = [];
          $w[0] = $args[0];
          if(isset($args[1])) {
            $w[1] = $args[1];
            if(isset($args[2]))
              $w[2] = $args[2];
          }
          $this->ExecuteWarp($sender, $w);
          return true;
      }//$switch 끝
    }//$command 끝
  }//함수 끝


  public function onSignChange(SignChangeEvent $event) {
    if($event->getLine(0) !== "워프") return;
    $player = $event->getPlayer();
    if(!$player->isOp()) {
      $player->sendMessage("§b§o[ 알림 ] §7권한이 없습니다.");
      $event->setCancelled();
      return;
    }
    if($event->getLine(1) == '') {
      $player->sendMessage("§b§o[ 알림 ] §7워프 생성법");
      $player->sendMessage("§b§o[ 알림 ] §71줄 : 워프");
      $player->sendMessage("§b§o[ 알림 ] §72줄 : [워프 이름]");
      $player->sendMessage("§b§o[ 알림 ] §73줄 : [워프 블럭] (표지판, 나무버튼, 돌버튼, 레버) > 비워두셔도 됩니다.");
      return;
    }
    $w = $this->makeW($event->getLine(1));
    if(!$this->isExistWarp($w)) {
      $player->sendMessage("§b§o[ 알림 ] §7존재하지 않는 워프입니다.");
      $event->setCancelled();
      return;
    }
    $mode = [];
    $block = $event->getBlock();
    if($block->getId() == 68){
      $mode['wall'] = true;
      $mode['dmg'] = $block->getDamage();
    } else {$mode['wall'] = false;}
    $pos = [];
    $pos['x'] = $block->getX();
    $pos['y'] = $block->getY();
    $pos['z'] = $block->getZ();
    $pos['level'] = $event->getBlock()->getLevel()->getName();
    $this->writeGateData($w, $pos);
    $event->setLine(0, "§b[ 워프 ]");
    $line2 = $event->getLine(2);
    switch ($line2) {
      case "표지판":
        $event->setLine(0, "§b[ 워프 ]");
        $event->setLine(2, '');
        break;
      case "나무버튼":
        if(!$mode['wall']) {
          $player->sendMessage("§b§o[ 알림 ] §7표지판을 벽에 설치해주세요.");
          $event->setCancelled();
          return;
        }
        $block->getLevel()->setBlock(new Vector3($pos['x'], $pos['y'], $pos['z']), Block::get(143, $mode['dmg']));
         break;
      case "레버":
        if($mode['wall']) {
          $player->sendMessage("§b§o[ 알림 ] §7표지판을 바닥에 설치해주세요.");
          $event->setCancelled();
          return;
        }
        else
          $block->getLevel()->setBlock(new Vector3($pos['x'], $pos['y'], $pos['z']), Block::get(69, 5)); //5 or 6
        break;
      case "돌버튼":
        if(!$mode['wall']) {
          $player->sendMessage("§b§o[ 알림 ] §7표지판을 벽에 설치해주세요.");
          $event->setCancelled();
          return;
        }
        $block->getLevel()->setBlock(new Vector3($pos['x'], $pos['y'], $pos['z']), Block::get(77, $mode['dmg']));
         break;
      default:
        if(!$mode['wall']) {
          $event->setLine(0, "§b[ 워프 ]");
          $event->setLine(2, '');
          break;
        }
        $block->getLevel()->setBlock(new Vector3($pos['x'], $pos['y'], $pos['z']), Block::get(77, $mode['dmg']));
         break;
    }
    if($mode['wall']) {
      $this->addTag($pos['x'], $pos['y'], $pos['z'], $block->getLevel(), "§f= §b워프 §f=\n§d".$event->getLine(1));
    } else {
      $this->addTag($pos['x'], $pos['y'], $pos['z'], $block->getLevel(), "§f= §b워프 §f=\n§d".$event->getLine(1));
    }
    $player->sendMessage("§b§o[ 알림 ] §7성공적으로 워프를 생성하였습니다.");
  }

  public function onBreak(BlockBreakEvent $event) {
    switch($event->getBlock()->getId()) {
      case 77:
      case 69:
      case 68:
      case 63:
      case 143:
        break;
      default:
        return;
    }
    $block = $event->getBlock();
    $tag = $block->getX().':'.$block->getY().':'.$block->getZ().'/'.$block->getLevel()->getName();
    if(!isset($this->gateData[$tag])) return;
    $player = $event->getPlayer();
    if(!$player->isOp()) {
      $player->sendMessage("§b§o[ 알림 ] §7워프를 삭제할 권한이 없습니다.");
      $event->setCancelled();
      return;
    }
    unset($this->gateData[$tag]);
    $this->delTag($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
    $player->sendMessage("§b§o[ 알림 ] §7성공적으로 워프를 삭제하였습니다.");
  }

  public function onInteract(PlayerInteractEvent $event) {
    switch($event->getBlock()->getId()) {
      case 77:
      case 69:
      case 68:
      case 63:
      case 143:
        break;
      default:
        return;
    }
    $block = $event->getBlock();
    $tag = $block->getX().':'.$block->getY().':'.$block->getZ().'/'.$block->getLevel()->getName();
     if(!isset($this->gateData[$tag])) return;
     $dat = $this->makeW($this->gateData[$tag]);
     if(!$this->isExistWarp($dat)) {
       $event->getPlayer()->sendMessage("§b§o[ 알림 ] §7해당 워프가 존재하지 않습니다.");
       $event->setCancelled();
       return;
     }
     $this->ExecuteWarp($event->getPlayer(), $dat);
  }





}//클래스 괄호

?>
