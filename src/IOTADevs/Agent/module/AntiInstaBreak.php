<?php

/**
 *
 * d8b  .d88888b. 88888888888     d8888 8888888b.
 * Y8P d88P" "Y88b    888        d88888 888  "Y88b
 *     888     888    888       d88P888 888    888
 * 888 888     888    888      d88P 888 888    888  .d88b.  888  888 .d8888b
 * 888 888     888    888     d88P  888 888    888 d8P  Y8b 888  888 88K
 * 888 888     888    888    d88P   888 888    888 88888888 Y88  88P "Y8888b.
 * 888 Y88b. .d88P    888   d8888888888 888  .d88P Y8b.      Y8bd8P       X88
 * 888  "Y88888P"     888  d88P     888 8888888P"   "Y8888    Y88P    88888P'
 *
 * This Program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with <plugin Name>.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author iOTADevs
 * @link http://iotadevs.github.io
 *
 */

declare(strict_types = 1);

namespace IOTADevs\Agent\module;

use IOTADevs\Agent\Main;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\entity\Effect;

class AntiInstaBreak extends AgentModule implements Listener{
	
	public const MODULE_NAME = "AntiInstaBreak";
	
	private $breakYe = [];

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerInteract(PlayerInteractEvent $event){
		if($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK){
			$this->breakYe[$event->getPlayer()->getRawUniqueId()] = floor(microtime(true) * 20);
		}
	}
	public function onBlockBreak(BlockBreakEvent $event){
		if(!$event->getInstaBreak()){
			do{
				$player = $event->getPlayer();
				if(!isset($this->breakYe[$uuid = $event->getPlayer()->getRawUniqueId()])){
					$event->setCancelled();
					break;
				}

				$item = $event->getItem();
				$block = $event->getBlock();

				$guessYe = ceil($block->getBreakTime($item) * 20);

				if($player->hasEffect(Effect::HASTE)){
					$guessYe *= 1 - (0.2 * $player->getEffect(Effect::HASTE)->getEffectLevel());
				}

				if($player->hasEffect(Effect::MINING_FATIGUE)){
					$guessYe *= 1 + (0.3 * $player->getEffect(Effect::MINING_FATIGUE)->getEffectLevel());
				}

				$guessYe -= 1;

				$realYe = ceil(microtime(true) * 20) - $this->breakYe[$uuid = $player->getRawUniqueId()];

				if($realYe < $guessYe){
					$event->setCancelled();
					break;
				}

				unset($this->breakYe[$uuid]);
			}while(false);
		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event){
		unset($this->breakYe[$event->getPlayer()->getRawUniqueId()]);
	}
}
