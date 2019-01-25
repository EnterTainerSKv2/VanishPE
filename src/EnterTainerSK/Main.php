
<?php

namespace EnterTainerSK;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {

    public $prefix = C::GRAY."(§a!§7)§r".C::WHITE." ";

    public $config;

    public $vanish = array();

    public function onEnable(){
        $this->getLogger()->info("Vanish by EnterTainerSK ENABLED!");
        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
            "Adventure_Vanish" => true
        ]);
        $this->config->set("Adventure_Vanish", true);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        $name = $sender->getName();
        if($command->getName() == "vanish") {
            if ($sender->hasPermission("vanish.spectate")) {
                if (!in_array($name, $this->vanish)) {
                    $this->vanish[] = $name;
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                    $sender->setNameTagVisible(false);
                    if($this->config->get("Adventure_Vanish") == true){
                        $sender->setGamemode(2);
                    }
                    $sender->sendMessage($this->prefix . C::GREEN . "You are now in Vanish!");
                    return true;
                } elseif (in_array($name, $this->vanish)) {
                    unset($this->vanish[array_search($name, $this->vanish)]);
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                    $sender->setNameTagVisible(true);
                    if($this->config->get("Adventure_Vanish") == true){
                        $sender->setGamemode(0);
                    }
                    $sender->setHealth(20);
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . C::RED . "You are no longer in Vanish!");
                    return true;
                }
            }
        }
		return true;
    }
}
