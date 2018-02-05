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

namespace IOTADevs\Agent;

use IOTADevs\Agent\entity\AutoAimBait;
use IOTADevs\Agent\module\AntiInstaBreak;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {
	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}

	public function onJoin(PlayerJoinEvent $ev){
		//file_put_contents($this->plugin->getDataFolder() . "skin" . $ev->getPlayer()->getName() . ".txt", base64_encode(serialize($ev->getPlayer()->namedtag->Skin)));
		if(!isset($this->plugin->warnings[$ev->getPlayer()->getName()])){
			$this->plugin->warnings[$ev->getPlayer()->getName()] = 0;
			$ev->getPlayer()->sendMessage(Main::getPrefix() . "I'm watching you...");
		} else {
			$ev->getPlayer()->sendMessage(Main::getPrefix() . "I'm still watching you...");
		}
		$baitNBT = Entity::createBaseNBT($ev->getPlayer()->asVector3()); // unreachable at first
		$bait = $this->plugin->baits[$ev->getPlayer()->getName()] = new AutoAimBait($ev->getPlayer()->getLevel(), $baitNBT, $ev->getPlayer());
		$bait->spawnTo($ev->getPlayer());
	}

	public function onQuit(PlayerQuitEvent $ev){
		if(isset($this->plugin->baits[$ev->getPlayer()->getName()])){
			$this->plugin->baits[$ev->getPlayer()->getName()]->close();
			unset($this->plugin->baits[$ev->getPlayer()->getName()]);
		}
	}

	public function onMove(PlayerMoveEvent $ev){
		$player = $ev->getPlayer();
		$entity = $this->plugin->baits[$player->getName()];

		$entity->x = $player->x;
		$entity->y = $player->y;
		$entity->z = $player->z;
	}

	public function onBreak(BlockBreakEvent $ev){
		Main::getModule(AntiInstaBreak::MODULE_NAME)->check([$ev]);
	}

	public function onTap(PlayerInteractEvent $ev){
		Main::getModule(AntiInstaBreak::MODULE_NAME)->check([$ev]);
	}
}