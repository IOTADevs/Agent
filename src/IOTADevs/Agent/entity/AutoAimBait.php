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

namespace IOTADevs\Agent\entity;

use IOTADevs\Agent\Main;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\utils\UUID;

class AutoAimBait extends Human {

	/** @var Player */
	private $player;

	public function __construct(Level $level, CompoundTag $nbt, Player $player){
		$nbt->Skin = clone Main::getSkin();
		parent::__construct($level, $nbt);

		$this->setNameTag("");
		$this->uuid = UUID::fromRandom();

		//$this->setGenericFlag(self::DATA_FLAG_INVISIBLE, true);
		$this->setGenericFlag(self::DATA_FLAG_NO_AI, true);
		$this->setGenericFlag(self::DATA_FLAG_AFFECTED_BY_GRAVITY, false);
		$this->setNameTagVisible(false);
		$this->setNameTagAlwaysVisible(false);
		$this->setScale(0.1);
		$this->player = $player;
	}

	public function getName(): string{
		return "BAIT";
	}

	public function entityBaseTick(int $tickDiff = 1): bool{
		return false;
	}

	public function attack(EntityDamageEvent $source){
		return false;
	}

	public function knockBack(Entity $attacker, float $damage, float $x, float $z, float $base = 0.4){
		return false;
	}
}