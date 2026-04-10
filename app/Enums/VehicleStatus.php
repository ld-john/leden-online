<?php
namespace App\Enums;

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
    case DAMAGED_RECALLED = 16;
    case AWAITING_DEALER_OPTIONS = 17;
    case DEALER_TRANSFER = 18;
    case ORDER_IN_QUERY = 19;
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
            self::DAMAGED_RECALLED => 'Damaged/Recalled',
            self::AWAITING_DEALER_OPTIONS => 'In Stock (Awaiting Dealer Options)',
            self::DEALER_TRANSFER => 'Dealer Transfer',
            self::ORDER_IN_QUERY => 'Order in Query',
        };
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
            self::DAMAGED_RECALLED,
            self::AWAITING_DEALER_OPTIONS,
            self::DEALER_TRANSFER,
            self::ORDER_IN_QUERY,
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
            self::DAMAGED_RECALLED,
            self::AWAITING_DEALER_OPTIONS,
            self::DEALER_TRANSFER,
            self::ORDER_IN_QUERY,
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

    public function isExcludedFromImport(): bool
    {
        return in_array($this, self::excludeFromPipeline());
    }
}

