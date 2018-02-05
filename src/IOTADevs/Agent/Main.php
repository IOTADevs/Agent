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

use CortexPE\utils\TextFormat;
use IOTADevs\Agent\handler\PacketHandler;
use IOTADevs\Agent\module\AgentModule;
use IOTADevs\Agent\module\AntiAutoAim;
use IOTADevs\Agent\module\AntiFly;
use IOTADevs\Agent\module\AntiNoClip;
use IOTADevs\Agent\task\AgentHeartbeat;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	/** @var Main */
	private static $instance;

	/** @var AgentModule[] */
	private $modules = [];

	/** @var array */
	public $configs = []; // yes this is an array. store the config values here...

	/** @var int[] */
	public $warnings = [];

	public function onEnable(){
		self::$instance = $this;

		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->configs = $this->getConfig()->getAll();

		$pluginManager = $this->getServer()->getPluginManager();
		$pluginManager->registerEvents(new EventListener($this), $this);
		$pluginManager->registerEvents(new PacketHandler($this), $this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new AgentHeartbeat($this), 10);

		$this->modules[] = new AntiFly();
		$this->modules[] = new AntiNoClip();
		//$this->modules[] = new AntiAutoAim();
		$this->modules[] = new AntiInstaBreak();
	}

	public static function getInstance() : Main{
		return self::$instance;
	}

	/**
	 * @return AgentModule[]
	 */
	public function getModules() : array{
		return $this->modules;
	}

	public static function getPrefix() : string {
		return TextFormat::DARK_GRAY . "Agent" . TextFormat::WHITE . "> ";
	}
}
