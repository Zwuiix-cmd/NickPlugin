<?php

namespace Zwuiix;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\utils\TextFormat as C;

class Main extends PluginBase implements Listener{
    function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if($sender instanceof Player){
            if($command->getName() === "nick"){
                $this->nick($sender);
            }
        } return true;
    }

    public function nick($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
              $this->randomNick($sender);
              break;
              case 2:
              $sender->sendMessage($this->getConfig()->get("reset-pseudonyme"));
              $name = $sender->getName();
              $sender->setNameTag($this->getConfig()->get("reset-nametag") . "\n$name");
              $sender->setDisplayName($name);
              break;
          }
        });
        $config = $this->getConfig();
        $name = $sender->getName();
        $form->setTitle($this->getConfig()->get("Title"));
		$form->setContent($this->getConfig()->get("Content"));
        $form->addButton($this->getConfig()->get("Boutton-Fermer"));
        $form->addButton($this->getConfig()->get("Activer"));
        $form->addButton($this->getConfig()->get("Desactiver"));
        $form->sendToPlayer($sender);
	}

    function randomNick(Player $player){
        $zahl = mt_rand(0, count($this->getConfig()->get("random-nicks")) -1 );
        $player->setNameTag($this->getConfig()->get("random-nametag") . "\n" . $this->getConfig()->get("random-nicks")[$zahl]);
        $player->setDisplayName($this->getConfig()->get("random-nicks")[$zahl]);
        $message = $this->getConfig()->get("nick-message");
        $player->sendMessage(str_replace("{nick}", $this->getConfig()->get("random-nicks")[$zahl], $message));
    }
}