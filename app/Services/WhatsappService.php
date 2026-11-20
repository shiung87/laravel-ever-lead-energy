<?php

namespace App\Services;

use App\Models\WebsiteSetting;

class WhatsappService
{
    public static function salesNumber(): string
    {
        return preg_replace('/[^0-9]/', '', (string) WebsiteSetting::getValue('whatsapp_sales_number', '60124472622'));
    }

    public static function dealerNumber(): string
    {
        return preg_replace('/[^0-9]/', '', (string) WebsiteSetting::getValue('whatsapp_dealer_number', '60124472622'));
    }

    public static function buildUrl(?string $message = null, string $type = 'sales'): string
    {
        $number = $type === 'dealer' ? static::dealerNumber() : static::salesNumber();
        $text = $message ?: (string) WebsiteSetting::getValue('whatsapp_default_message', 'Hello Everlead Energy, I would like to enquire about a car battery.');

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
    }
}
