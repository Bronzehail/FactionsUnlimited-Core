<?php
namespace FacCore;

use FacCore\Commands\RemoveWarpCommand;
use FacCore\Commands\RulesCommand;
use FacCore\Commands\SetWarpCommand;
use FacCore\Commands\SpawnCommand;
use FacCore\Commands\TPACommand;
use FacCore\Commands\WarpCommand;
use FacCore\Events\EventListener;
use FacCore\Tasks\AutoRestartTask;
use FacCore\Commands\FlyCommand;
use FacCore\Alert\Alerts;

use pocketmine\event\Listener;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
	/** @var BaseLang $baseLang */
	private $baseLang = null;
	/** @var Config $IPLocks */
	private $IPLocks;
	public function onLoad() {
		$this->getLogger()->notice(TextFormat::GREEN . "Starting SkyBlock Core");
		$this->saveDefaultConfig();
		$this->saveResource("profanity.yml");
		$this->IPLocks = new Config($this->getDataFolder()."IPLocker.yml");
		//Messages
		$lang = $this->getConfig()->get("language", BaseLang::FALLBACK_LANGUAGE);
		$this->baseLang = new BaseLang($lang, $this->getFile() . "resources/");
		//Commands
		$this->getServer()->getCommandMap()->registerAll($this->getDescription()->getName(), [
			new FlyCommand($this),
			new RemoveWarpCommand($this),
			new RulesCommand($this),
			new SetWarpCommand($this),
			new SpawnCommand($this),
			//new TPACommand($this), //TODO implement all tp commands and a timeout system
			new WarpCommand($this)
		]);
	}
	public function onEnable() {
		$this->getLogger()->notice("LOADING.....".base64_decode(""));
		// Events
		new EventListener($this);
		//Tasksmk
		$this->getServer()->getScheduler()->scheduleDelayedTask(new AutoRestartTask($this), 20 * 60 * 60 * 12); // Delay: 12 Hours
		$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new Alerts($this), 2000, 2000); // Repeats every 100 seconds
		//TODO: other tasks
	}
	public function onDisable() {
		$this->getLogger()->notice(TextFormat::RED ."Shutting down Core");
	}
	/**
	 * @return BaseLang
	 */
	public function getLanguage() : BaseLang {
		return $this->baseLang;
	}
	/**
	 * @return Config
	 */
	public function getIPLocks() : Config {
		return $this->IPLocks;
	}
	/**
	 * @return Config
	 */
	public function getWarpsConfig() : Config {
		return new Config($this->getDataFolder()."warps.yml", Config::YAML);
	}

	# API

	/**
	 * This code was borrowed from xBeastMode
	 * @link https://forums.pmmp.io/threads/help-with-stripos.3766/#post-36031
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public function filterProfanity(string $message) : string {
		/** @var string[] $_profanity */
		$_profanity = (new Config($this->getDataFolder()."profanity.yml", Config::YAML))->getAll();
		$ltrs = "$$|ss,ã|a,å|a,ā|a,ą|a,ª|a,à|a,á|a,â|a,ä|a,æ|a,č|c,ç|c,ć|c,ę|e,ë|e,ē|e,ė|e,è|e,é|e,ê|e,į|i,ī|i,ì|i,ï|i,î|i,í|i,º|o,õ|o,ō|o,ø|o,œ|o,ò|o,ö|o,ô|o,ó|o,ū|u,ü|u,ù|u,û|u,ú|u"; //TODO: add more characters to the list
		$ltrs = explode(',', $ltrs);
		array_walk($ltrs, create_function('&$v', '$v = explode("|", $v);'));
		foreach($ltrs as $ltr){
			$message = str_replace(mb_strtolower($ltr[0], 'UTF-8'), $ltr[1], $message);
			$message = str_replace(mb_strtoupper($ltr[0], 'UTF-8'), $ltr[1], $message);
		}
		$profanity = array_keys($_profanity);
		$replacement = array_values($_profanity);
		return $message = str_ireplace($profanity, $replacement, $message);
	}
}
