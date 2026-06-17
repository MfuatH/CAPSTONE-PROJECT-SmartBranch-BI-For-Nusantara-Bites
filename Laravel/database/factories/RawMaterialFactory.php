<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RawMaterialFactory extends Factory
{
    public function definition(): array
    {
        $materials = ['Biji Kopi Gayo', 'Biji Kopi Robusta', 'Susu UHT Full Cream', 'Gula Aren Cair', 'Sirup Karamel', 'Matcha Powder', 'Cup Plastik 16oz', 'Sedotan Kertas'];
        $units = ['Kg', 'Liter', 'Gram', 'Pcs'];

        return [
            'name' => $this->faker->randomElement($materials),
            'sku' => 'BB-' . $this->faker->unique()->numerify('####'),
            'stock' => $this->faker->randomFloat(2, 5, 200),
            'unit' => $this->faker->randomElement($units),
            'price_per_unit' => $this->faker->randomFloat(2, 15000, 250000),
        ];
    }
}