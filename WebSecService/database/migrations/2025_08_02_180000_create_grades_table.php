<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('course_name');
            $table->string('course_code');
            $table->integer('credit_hours');
            $table->string('term'); // مثل: الفصل الأول 2024
            $table->decimal('grade', 3, 2); // درجة من 4.00
            $table->string('letter_grade'); // A, B+, B, C+, C, D, F
            $table->decimal('gpa', 3, 2)->default(0.00);
            $table->decimal('points', 5, 2)->default(0.00); // النقاط المحسوبة
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // فهرس مركب لمنع تكرار نفس المادة لنفس الطالب في نفس الفصل
            $table->unique(['student_id', 'course_code', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
