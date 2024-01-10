<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;

use function PHPUnit\Framework\callback;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    private const PRODUCT_NAMES = [
        'Produkt A',
        'Produkt B',
        'Produkt C',
        'Produkt D'
    ];

    private const PRODUCT_PRICES = [
        22.5,
        123.99,
        64,
        18.9
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $r = mt_rand(0 ,3);
        return [
            'customer_tax_id'   => fake()->randomNumber(5, true) . fake()->randomNumber(5, true),
            'seller_tax_id'     => fake()->randomNumber(5, true) . fake()->randomNumber(5, true),
            'product_name'      => self::PRODUCT_NAMES[$r],
            'net_price'         => self::PRODUCT_PRICES[$r]
        ];
    }
}
