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
use pocketmine\Player;

abstract class AgentModule {
	public const MODULE_NAME = "";

	abstract public function check(array $factors);

	public function hacking(Player $player, AgentModule $module){
		$this->broadcastHackingMessage($player, $module->getConfigEntry());
		if($this->addWarning($player) < Main::getInstance()->configs["warningsBeforeKick"]){
			$this->revertPlayer($player);
		} else {
			$this->kickPlayer($player, $module->getConfigEntry());
		}
	}

	public function addWarning(Player $player) : int{
		if(isset(Main::getInstance()->warnings[$player->getName()])){
			Main::getInstance()->warnings[$player->getName()]++;
		} else {
			Main::getInstance()->warnings[$player->getName()] = 1; // starting is 0...
		}
		return Main::getInstance()->warnings[$player->getName()];
	}

	abstract public function revertPlayer(Player $player);

	public function broadcastHackingMessage(Player $player, string $configEntryName) : string {
		return Main::getPrefix() . str_replace(["{player}"], [$player->getName()], Main::getInstance()->configs["messages"][$configEntryName]);
	}

	public function kickPlayer(Player $player, string $configEntryName) : bool {
		return $player->kick(Main::getInstance()->configs["kickMessages"][$configEntryName],false);
	}

	abstract public function getConfigEntry() : string;
}