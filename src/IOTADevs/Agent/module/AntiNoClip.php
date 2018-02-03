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
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\Player;
use pocketmine\Server;

class AntiNoClip extends AgentModule {
	public const MODULE_NAME = "AntiNoClip";

	public function check(Player $player, AdventureSettingsPacket $pk){
		$config = Main::getInstance()->configs;
		if ($pk->getFlag(AdventureSettingsPacket::NO_CLIP) && !$player->isSpectator()) {
			Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], $config["messages"]["no-clip"])); // this is an array so that its easily extensible
			if($this->addWarning($player) < Main::getInstance()->configs["warningsBeforeKick"]){
				$player->sendSettings();
			} else {
				$player->kick($config["kickMessages"]["no-clip"],false);
			}
		}
	}
}