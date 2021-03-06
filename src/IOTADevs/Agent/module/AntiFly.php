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

use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\Player;

class AntiFly extends AgentModule {
	public const MODULE_NAME = "AntiFly";

	public function check(array $factors){
		$player = $factors[0];
		$pk = $factors[1];

		if($player instanceof Player && $pk instanceof AdventureSettingsPacket){
			if(($pk->getFlag(AdventureSettingsPacket::ALLOW_FLIGHT) || $pk->getFlag(AdventureSettingsPacket::FLYING)) && !$player->getAllowFlight()){
				$this->hacking($player, $this);
			}
		} else {
			throw new ModuleException("Invalid Factors given");
		}
	}

	public function revertPlayer(Player $player){
		$player->sendSettings();
	}

	public function getConfigEntry(): string{
		return "fly";
	}
}