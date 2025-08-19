<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female'];
        $roles = ['admin', 'teacher', 'student'];
        $statuses = ['active', 'inactive', 'suspended'];
        $securityQuestions = [
            'ما هو اسم أول مدرسة التحقت بها؟',
            'ما هو اسم أول حي سكنت فيه؟',
            'ما هو اسم أول حيوان أليف امتلكته؟',
            'ما هو اسم أول مدينة زرتها؟',
            'ما هو اسم أول كتاب قرأته؟'
        ];
        
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
        $username = strtolower(str_replace(' ', '', $name)) . fake()->numberBetween(1, 999);
        
        return [
            'name' => $name,
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'role' => fake()->randomElement($roles),
            'status' => fake()->randomElement($statuses),
            'admin' => fake()->boolean(10), // 10% chance of being admin
            'security_question' => fake()->randomElement($securityQuestions),
            'security_answer' => fake()->word(),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is a teacher.
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'teacher',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is a student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'student',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
