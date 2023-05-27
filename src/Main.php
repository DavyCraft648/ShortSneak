<?php
declare(strict_types=1);

namespace DavyCraft648\ShortSneak;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;
use function array_merge;

class Main extends \pocketmine\plugin\PluginBase{

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function(DataPacketSendEvent $event) : void{
			foreach($event->getPackets() as $packet){
				if($packet instanceof StartGamePacket){
					$packet->levelSettings->experiments = new Experiments(array_merge($packet->levelSettings->experiments->getExperiments(), [
						"short_sneaking" => true
					]), true);
				}
			}
		}, EventPriority::HIGHEST, $this);
		$this->getServer()->getPluginManager()->registerEvent(PlayerToggleSneakEvent::class, function(PlayerToggleSneakEvent $event) : void{
			$player = $event->getPlayer();
			if(!$event->isSneaking()){
				($method = new \ReflectionMethod($player, "recalculateSize"))->setAccessible(true);
				$method->invoke($player);
			}elseif(!$player->isSwimming() && !$player->isGliding()){
				($prop = new \ReflectionProperty($player->size, "height"))->setAccessible(true);
				$prop->setValue($player->size, 1.5 * $player->getScale());
				($prop = new \ReflectionProperty($player->size, "eyeHeight"))->setAccessible(true);
				$prop->setValue($player->size, 1.32 * $player->getScale());
			}
		}, EventPriority::MONITOR, $this);
	}
}
