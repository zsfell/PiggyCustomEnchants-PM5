<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCustomEnchants\enchants\weapons;

use DaPigGuy\PiggyCustomEnchants\enchants\ReactiveEnchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Event;
use pocketmine\inventory\Inventory;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\Item;
use pocketmine\player\Player;

class DisarmingEnchant extends ReactiveEnchantment
{
    public string $name = "Disarming";
    public int $rarity = Rarity::UNCOMMON;

    public function react(Player $player, Item $item, Inventory $inventory, int $slot, Event $event, int $level, int $stack): void
    {
        if ($event instanceof EntityDamageByEntityEvent) {
            $entity = $event->getEntity();
            if ($entity instanceof Player) {
                if (count($contents = $entity->getInventory()->getContents()) > 0) {
                    $item = $contents[array_rand($contents)];
                    $entity->getInventory()->removeItem($item);
                    $entity->dropItem($item);
                }
            }
        }
    }
}