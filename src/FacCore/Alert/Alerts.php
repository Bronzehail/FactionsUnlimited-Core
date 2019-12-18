<?php
namespace FacCore\Alert;

use pocketmine\Server;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class Alerts extends PluginTask {
	public function onRun(int $currentTick) {
		$input = [
			"• Tip » Vote at https://minecraftpocket-servers.com/server/91526/vote/ to get crate keys and coins.",
			"• Tip » Do ".TextFormat::WHITE."/rules".TextFormat::GRAY." to get a list of our Server Rules!",
			"• Tip » Staff abusing? DM us on twitter @Bronzehail with proof and We'll take action for you.",
			"• NOTICE » We are still in BETA. Please report any bugs on Twitter.",
			"• Tip » Follow us on Twitter @Bronzehail to get the Latest Server Updates and events that goes on.",
			"• SkyBlock » We hope you're Enjoying your GamePlay! Send some FeedBack by DMing us on Twitter",
			"• Tip » Do ".TextFormat::WHITE."is help".TextFormat::GRAY." to see all SkyBlock commands.",
			"• Tip » Do ".TextFormat::WHITE."/is create".TextFormat::GRAY." to make an Island.",
			"• Need Help? Try typing ".TextFormat::WHITE."/help",
			"• Thanks for playing on SkyBlock Unlimited!",
			"• Hacking is NOT allowed on SkyBlock Unlimited, Disable client mods before playing",
			"• We know our server is not perfect but we will always try to improve it."
		];
		$messages = array_rand($input);
		Server::getInstance()->broadcastMessage(TextFormat::GRAY . $input[$messages]);
	}
}
