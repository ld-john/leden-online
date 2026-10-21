<?php
namespace App\Enums;

use mysql_xdevapi\Collection;
use function Laravel\Prompts\select;

enum VehicleStatus : int
{
    case IN_STOCK = 1;
    case READY_FOR_DELIVERY = 3;
    case FACTORY_ORDER = 4;
    case AWAITING_DELIVERY_CONFIRMATION = 5;
    case DELIVERY_BOOKED = 6;
    case COMPLETED_ORDERS = 7;
    case EUROPE_VHC = 10;
    case UK_VHC = 11;
    case AT_CONVERTER = 12;
    case AWAITING_SHIP = 13;
    case RECALL = 14;
    case IN_STOCK_REGISTERED = 15;
    case DAMAGED = 16;
    case AWAITING_DEALER_OPTIONS = 17;
    case DEALER_TRANSFER = 18;
    case ORDER_IN_QUERY = 19;
    case REGISTERED_RECALL = 20;
    case DEFERRED = 21;
    public function label(): string
    {
        return match($this) {
            self::IN_STOCK => 'In Stock',
            self::READY_FOR_DELIVERY => 'Ready for Delivery',
            self::FACTORY_ORDER => 'Factory Order',
            self::AWAITING_DELIVERY_CONFIRMATION => 'Awaiting Delivery Confirmation',
            self::DELIVERY_BOOKED => 'Delivery Booked',
            self::COMPLETED_ORDERS => 'Completed Orders',
            self::EUROPE_VHC => 'Europe VHC',
            self::UK_VHC => 'UK VHC',
            self::AT_CONVERTER => 'At Converter',
            self::AWAITING_SHIP => 'Awaiting Ship',
            self::RECALL => 'Recall',
            self::IN_STOCK_REGISTERED => 'In Stock (Registered)',
            self::DAMAGED => 'Damaged',
            self::AWAITING_DEALER_OPTIONS => 'In Stock (Awaiting Dealer Options)',
            self::DEALER_TRANSFER => 'Dealer Transfer',
            self::ORDER_IN_QUERY => 'Order in Query',
            self::REGISTERED_RECALL => 'Registered (Recall)',
            self::DEFERRED => 'Deferred'
        };
    }

    public function slug(): string
    {
        return match($this) {
            self::IN_STOCK => 'in-stock',
            self::READY_FOR_DELIVERY => 'ready-for-delivery',
            self::FACTORY_ORDER => 'factory-order',
            self::AWAITING_DELIVERY_CONFIRMATION => 'awaiting-delivery-confirmation',
            self::DELIVERY_BOOKED => 'delivery-booked',
            self::COMPLETED_ORDERS => 'completed-orders',
            self::EUROPE_VHC => 'europe-vhc',
            self::UK_VHC => 'uk-vhc',
            self::AT_CONVERTER => 'at-converter',
            self::AWAITING_SHIP => 'awaiting-ship',
            self::RECALL => 'recall',
            self::IN_STOCK_REGISTERED => 'in-stock-registered',
            self::DAMAGED => 'damaged',
            self::AWAITING_DEALER_OPTIONS => 'in-stock-awaiting-dealer-options',
            self::DEALER_TRANSFER => 'dealer-transfer',
            self::ORDER_IN_QUERY => 'order-in-query',
            self::REGISTERED_RECALL => 'registered-recall',
            self::DEFERRED => 'deferred'
        };
    }

    /**
     * Helper to find the Enum instance by its slug
     */
    public static function tryFromSlug(string $slug): ?self
    {
        foreach (self::cases() as $status) {
            if ($status->slug() === $slug) {
                return $status;
            }
        }
        return null;
    }

    public static function deliveryStatuses(): array
    {
        return [
            self::READY_FOR_DELIVERY,
            self::AWAITING_DELIVERY_CONFIRMATION,
            self::DELIVERY_BOOKED,
        ];
    }

    public function isDeliveryEligible(): bool
    {
        return in_array($this, [
            self::IN_STOCK,
            self::READY_FOR_DELIVERY,
            self::AWAITING_DELIVERY_CONFIRMATION,
            self::DELIVERY_BOOKED,
            self::IN_STOCK_REGISTERED,
        ]);
    }

    public static function orderBankStatuses(): array
    {
        return [
            self::IN_STOCK,
            self::FACTORY_ORDER,
            self::EUROPE_VHC,
            self::UK_VHC,
            self::AT_CONVERTER,
            self::AWAITING_SHIP,
            self::RECALL,
            self::IN_STOCK_REGISTERED,
            self::DAMAGED,
            self::AWAITING_DEALER_OPTIONS,
            self::DEALER_TRANSFER,
            self::ORDER_IN_QUERY,
            self::REGISTERED_RECALL
        ];
    }

    public static function excludeFromPipeline(): array
    {
        return [
            self::IN_STOCK,
            self::READY_FOR_DELIVERY,
            self::AWAITING_DELIVERY_CONFIRMATION,
            self::DELIVERY_BOOKED,
            self::COMPLETED_ORDERS,
            self::RECALL,
            self::IN_STOCK_REGISTERED,
            self::DAMAGED,
            self::AWAITING_DEALER_OPTIONS,
            self::DEALER_TRANSFER,
            self::ORDER_IN_QUERY,
            self::REGISTERED_RECALL
        ];
    }

    public function isOrderBank(): bool
    {
        return in_array($this, self::orderBankStatuses());
    }

    public function isDeliveryManaged(): bool
    {
        return in_array($this, self::deliveryStatuses());
    }

    public function isInStock(): bool
    {
        return in_array($this, [self::IN_STOCK, self::IN_STOCK_REGISTERED]);
    }

    public function isRecalled(): bool
    {
        return in_array($this, [self::RECALL, self::REGISTERED_RECALL, self::DAMAGED]);
    }

    public function isExcludedFromImport(): bool
    {
        return in_array($this, self::excludeFromPipeline());
    }

    public static function fromLabel(string $label): self
    {
        return match (strtolower(trim($label))) {
            'in stock' => self::IN_STOCK,
            'ready for delivery' => self::READY_FOR_DELIVERY,
            'uk vhc' => self::UK_VHC,
            'delivery booked' => self::DELIVERY_BOOKED,
            'completed orders' => self::COMPLETED_ORDERS,
            'europe vhc' => self::EUROPE_VHC,
            'at converter' => self::AT_CONVERTER,
            'awaiting ship' => self::AWAITING_SHIP,
            default => self::FACTORY_ORDER,
        };
    }

    public static function completedStatuses() : array
    {
        return [
            self::COMPLETED_ORDERS
        ];
    }

    public static function bookedStatuses() : array
    {
        return [
            self::DELIVERY_BOOKED
        ];
    }

    public static function mainSearchBase() : array
    {
        $exclude = array_merge(
            self::completedStatuses(),
            self::bookedStatuses()
        );

        return collect(self::cases())
            ->reject(fn(self $status) => in_array($status, $exclude))
            ->toArray();
    }
}

