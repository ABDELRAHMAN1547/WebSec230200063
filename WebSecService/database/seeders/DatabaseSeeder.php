<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Grade;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء 10 طلاب فقط
        Student::factory(10)->create();
        
        // إنشاء 15 مستخدم تجريبي
        User::factory(15)->create();
        
        // إنشاء مستخدم مدير افتراضي (إذا لم يكن موجوداً)
        if (!User::where('username', 'admin')->exists()) {
            User::factory()->admin()->create([
                'name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'admin' => true,
                'security_question' => 'ما هو اسم أول مدرسة التحقت بها؟',
                'security_answer' => 'مدرسة النور',
            ]);
        }
        
        // إنشاء درجات تجريبية
        $students = Student::all();
        $terms = ['الفصل الأول 2024', 'الفصل الثاني 2024', 'الفصل الصيفي 2024', 'الفصل الأول 2025'];
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
        
        foreach ($students as $student) {
            foreach ($terms as $term) {
                // اختيار مواد عشوائية لكل طالب في كل فصل
                $selectedCourses = fake()->randomElements($courses, fake()->numberBetween(3, 5));
                
                foreach ($selectedCourses as $course) {
                    // التحقق من عدم وجود درجة لنفس الطالب والمادة والفصل
                    $existingGrade = Grade::where('student_id', $student->id)
                        ->where('course_code', $course['code'])
                        ->where('term', $term)
                        ->first();
                    
                    if (!$existingGrade) {
                        $grade = fake()->randomFloat(2, 1.0, 4.0);
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
                        
                        Grade::create([
                            'student_id' => $student->id,
                            'course_name' => $course['name'],
                            'course_code' => $course['code'],
                            'credit_hours' => $course['hours'],
                            'term' => $term,
                            'grade' => $grade,
                            'letter_grade' => $letterGrade,
                            'gpa' => $grade,
                            'points' => $grade * $course['hours'],
                            'notes' => fake()->optional()->sentence(),
                        ]);
                    }
                }
            }
        }
    }
}
