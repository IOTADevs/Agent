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
use pocketmine\entity\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

class AntiInstaBreak extends AgentModule {

	public const MODULE_NAME = "AntiInstaBreak";

	/** @var array */
	private $timeOfClick = [];

	public function check(array $factors){
		$ev = $factors[0];

		if($ev instanceof PlayerInteractEvent){
			if($ev->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK){
				$this->timeOfClick[$ev->getPlayer()->getName()] = floor(microtime(true) * 20);
			}
		}elseif($ev instanceof BlockBreakEvent){
			if(!$ev->getInstaBreak()){
				$player = $ev->getPlayer();
				$item = $ev->getItem();
				$block = $ev->getBlock();

				$breakTime = ceil($block->getBreakTime($item) * 20);

				if($player->hasEffect(Effect::HASTE)){
					$breakTime *= 1 - (0.2 * $player->getEffect(Effect::HASTE)->getEffectLevel());
				}

				if($player->hasEffect(Effect::MINING_FATIGUE)){
					$breakTime *= 1 + (0.3 * $player->getEffect(Effect::MINING_FATIGUE)->getEffectLevel());
				}

				$diff = ceil(microtime(true) * 20) - $this->timeOfClick[$player->getName()];

				if($diff < ($breakTime - Main::getInstance()->configs[$this->getConfigEntry()]["decrementBreakTime"])){
					$ev->setCancelled();
					$this->hacking($player, $this);
				}
			}
		}else{
			throw new ModuleException("Invalid Factors given");
		}
	}

	public function revertPlayer(Player $player){} // handled on check() method

	public function getConfigEntry(): string{
		return "insta-break";
	}
}
