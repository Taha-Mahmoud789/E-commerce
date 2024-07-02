<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    private $arabicWords = [
        'سلام', 'مرحبا', 'شكرا', 'حب', 'سعادة', 'أمل', 'قوة', 'نجاح', 'سلامة', 'صداقة'
    ];

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->word,
            'name_ar' => $this->faker->unique()->randomElement($this->arabicWords),
        ];
    }
}
