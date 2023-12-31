<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCustomEnchants\enchants\armor\chestplate;

use DaPigGuy\PiggyCustomEnchants\enchants\CustomEnchant;
use DaPigGuy\PiggyCustomEnchants\enchants\TickingEnchantment;
use pocketmine\inventory\Inventory;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\io\GlobalItemDataHandlers;

class ChickenEnchant extends TickingEnchantment
{
    public string $name = "Chicken";
    public int $rarity = Rarity::UNCOMMON;

    public int $usageType = CustomEnchant::TYPE_CHESTPLATE;
    public int $itemType = CustomEnchant::ITEM_TYPE_CHESTPLATE;

    public function getDefaultExtraData(): array
    {
        return ["treasureChanceMultiplier" => 5, "treasures" => ["266:0:1"], "interval" => 1200 * 5];
    }

    public function tick(Player $player, Item $item, Inventory $inventory, int $slot, int $level): void
    {
        if (mt_rand(0, 100) <= $this->extraData["treasureChanceMultiplier"] * $level) {
            $drops = $this->plugin->getConfig()->getNested("chicken.drops", $this->extraData["treasures"]);
            if (!is_array($drops)) {
                $drops = [$drops];
            }
            $drop = array_rand($drops);
            $drop = explode(":", $drops[$drop]);

            $itemify = GlobalItemDataHandlers::getUpgrader()->upgradeItemTypeDataInt((int)$drop[0], (int)$drop[1], (int)$drop[2], null);
            $itemify = GlobalItemDataHandlers::getDeserializer()->deserializeStack($itemify);

            $item = count($drop) < 3 ? VanillaItems::GOLD_INGOT() : $itemify;
            $vowels = ["a", "e", "i", "o", "u"];
            $player->getWorld()->dropItem($player->getPosition(), $item, $player->getDirectionVector()->multiply(-0.4));
            $player->sendTip(TextFormat::GREEN . "You have laid a" . (in_array(strtolower($item->getName()[0]), $vowels) ? "n " : " ") . $item->getName() . "...");
        } else {
            $player->getWorld()->dropItem($player->getPosition(), VanillaItems::EGG(), $player->getDirectionVector()->multiply(-0.4));
            $player->sendTip(TextFormat::GREEN . "You have laid an egg.");
        }
    }

    public function getTickingInterval(): int
    {
        return $this->extraData["interval"];
    }
}