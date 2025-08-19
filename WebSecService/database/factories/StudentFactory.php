<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female'];
        $statuses = ['active', 'inactive', 'graduated'];
        
        // أسماء عربية للذكور
        $maleNames = [
            'أحمد محمد علي',
            'محمد عبدالله حسن',
            'علي أحمد محمود',
            'عبدالله محمد سعيد',
            'حسن علي أحمد',
            'محمود محمد علي',
            'سعيد عبدالله حسن',
            'عمر أحمد محمد',
            'يوسف علي محمود',
            'خالد محمد سعيد'
        ];
        
        // أسماء عربية للإناث
        $femaleNames = [
            'فاطمة أحمد علي',
            'عائشة محمد حسن',
            'مريم عبدالله محمود',
            'خديجة علي أحمد',
            'زينب محمد سعيد',
            'نور الهدى أحمد علي',
            'سارة محمد حسن',
            'ليلى عبدالله محمود',
            'رنا علي أحمد',
            'هدى محمد سعيد'
        ];
        
        $gender = fake()->randomElement($genders);
        $name = $gender === 'male' ? fake()->randomElement($maleNames) : fake()->randomElement($femaleNames);
        
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'age' => fake()->numberBetween(16, 50),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'gender' => $gender,
            'student_id' => 'STU' . fake()->unique()->numberBetween(1000, 9999),
            'status' => fake()->randomElement($statuses),
            'enrollment_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * Indicate that the student is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the student is graduated.
     */
    public function graduated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'graduated',
        ]);
    }

    /**
     * Indicate that the student is young (16-25 years old).
     */
    public function young(): static
    {
        return $this->state(fn (array $attributes) => [
            'age' => fake()->numberBetween(16, 25),
        ]);
    }

    /**
     * Indicate that the student is adult (26-40 years old).
     */
    public function adult(): static
    {
        return $this->state(fn (array $attributes) => [
            'age' => fake()->numberBetween(26, 40),
        ]);
    }
} 