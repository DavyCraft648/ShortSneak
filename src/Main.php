<?php
declare(strict_types=1);

namespace DavyCraft648\ShortSneak;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerToggleSneakEvent;

class Main extends \pocketmine\plugin\PluginBase{

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(PlayerToggleSneakEvent::class, function(PlayerToggleSneakEvent $event) : void{
			$player = $event->getPlayer();
			if(!$event->isSneaking()){
				(new \ReflectionMethod($player, "recalculateSize"))->invoke($player);
			}elseif(!$player->isSwimming() && !$player->isGliding()){
				(new \ReflectionProperty($player->size, "height"))->setValue($player->size, 1.5 * $player->getScale());
				(new \ReflectionProperty($player->size, "eyeHeight"))->setValue($player->size, 1.32 * $player->getScale());
			}
		}, EventPriority::MONITOR, $this);
	}
}
