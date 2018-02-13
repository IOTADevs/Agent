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

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\PlayerMoveEvent;
use pocketmine\entity\Effect;

class AntiSpeed extends AgentModule implements Listener{
	
	public const MODULE_NAME = "AntiSpeed";
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function check(array $factors){
		$ev = $factors[0];
		
	    if($ev inctanceof PlayerMoveEvent){
            $player = $ev->getPlayer();
		    $from = $ev->getFrom();
		    $to = $ev->getTo();
		
		    if(!$player->hasEffect(Effect::SPEED)){
		        if($from->distance($to) > 1.4){
				$ev->setCancelled();
			}
		    }elseif($from->distance($to) > 2.1){
				$ev->setCancelled();
		    }   
	    }else{
		        throw new ModuleException("Invalid Factors Given");
	         }
	    }
        }    
    public function revertPlayer(Player $player){}
    
    public function getConfigEntry(): string{
    	return "speed";
    }
}
