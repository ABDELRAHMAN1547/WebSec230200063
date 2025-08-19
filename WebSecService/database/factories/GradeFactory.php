<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            ['name' => 'الرياضيات', 'code' => 'MATH101', 'hours' => 3],
            ['name' => 'الفيزياء', 'code' => 'PHYS101', 'hours' => 4],
            ['name' => 'الكيمياء', 'code' => 'CHEM101', 'hours' => 4],
            ['name' => 'اللغة الإنجليزية', 'code' => 'ENG101', 'hours' => 3],
            ['name' => 'البرمجة', 'code' => 'CS101', 'hours' => 4],
            ['name' => 'قواعد البيانات', 'code' => 'CS201', 'hours' => 3],
            ['name' => 'شبكات الحاسوب', 'code' => 'CS301', 'hours' => 3],
            ['name' => 'الذكاء الاصطناعي', 'code' => 'CS401', 'hours' => 4],
            ['name' => 'التاريخ', 'code' => 'HIST101', 'hours' => 3],
            ['name' => 'الجغرافيا', 'code' => 'GEO101', 'hours' => 3],
        ];

        $terms = [
            'الفصل الأول 2024',
            'الفصل الثاني 2024',
            'الفصل الصيفي 2024',
            'الفصل الأول 2025',
            'الفصل الثاني 2025',
        ];

        $course = fake()->randomElement($courses);
        $term = fake()->randomElement($terms);
        $grade = fake()->randomFloat(2, 1.0, 4.0);
        
        // تحويل الدرجة إلى حرف
        $letterGrade = match(true) {
            $grade >= 3.70 => 'A',
            $grade >= 3.30 => 'A-',
            $grade >= 3.00 => 'B+',
            $grade >= 2.70 => 'B',
            $grade >= 2.30 => 'B-',
            $grade >= 2.00 => 'C+',
            $grade >= 1.70 => 'C',
            $grade >= 1.30 => 'C-',
            $grade >= 1.00 => 'D+',
            $grade >= 0.70 => 'D',
            default => 'F'
        };

        return [
            'student_id' => Student::factory(),
            'course_name' => $course['name'],
            'course_code' => $course['code'],
            'credit_hours' => $course['hours'],
            'term' => $term,
            'grade' => $grade,
            'letter_grade' => $letterGrade,
            'gpa' => $grade,
            'points' => $grade * $course['hours'],
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the grade is excellent (A).
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 3.70, 4.00),
            'letter_grade' => 'A',
        ]);
    }

    /**
     * Indicate that the grade is good (B).
     */
    public function good(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 2.70, 3.29),
            'letter_grade' => 'B',
        ]);
    }

    /**
     * Indicate that the grade is average (C).
     */
    public function average(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 2.00, 2.69),
            'letter_grade' => 'C',
        ]);
    }

    /**
     * Indicate that the grade is for a specific term.
     */
    public function forTerm($term): static
    {
        return $this->state(fn (array $attributes) => [
            'term' => $term,
        ]);
    }
}
