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
use pocketmine\level\Level;
use pocketmine\math\Vector2;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\Server;
use pocketmine\utils\UUID;

class Agent extends Human {

	public function __construct(Level $level, CompoundTag $nbt){
		$nbt->Skin = clone Main::getSkin();
		parent::__construct($level, $nbt);

		$this->setNameTag("Agent");
		$this->uuid = UUID::fromRandom();
		$this->setNameTagVisible(true);
		$this->setNameTagAlwaysVisible(true);
	}

	public function getName(): string{
		return "Agent";
	}

	public function lookAtEntity(Entity $entity){
		$xdiff = $entity->x - $this->x;
		$zdiff = $entity->z - $this->z;
		$angle = atan2($zdiff, $xdiff);
		$yaw = (($angle * 180) / M_PI) - 90;
		$ydiff = $entity->y - $this->y;
		$v = new Vector2($this->x, $this->z);
		$dist = $v->distance($entity->x, $entity->z);
		$angle = atan2($dist, $ydiff);
		$pitch = (($angle * 180) / M_PI) - 90;
		$pk = new MovePlayerPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->position = $this->asVector3()->add(0, $this->getEyeHeight(), 0);
		$pk->yaw = $yaw;
		$pk->pitch = $pitch;
		$pk->headYaw = $yaw;
		$pk->onGround = $this->onGround;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		Server::getInstance()->broadcastPacket($this->getViewers(), $pk);
	}
}