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
        Schema::create('templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('style');
            $table->text('description')->nullable();
            $table->jsonb('structure')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('template_id')->nullable();
            $table->string('title');
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
        });

        Schema::create('resume_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resume_id');
            $table->string('section_type'); // contact, work, education, skills, projects, summary, languages
            $table->jsonb('content');
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
            $table->unique(['resume_id', 'section_type']);
        });

        Schema::create('resume_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resume_id');
            $table->integer('version_number');
            $table->jsonb('resume_data');
            $table->timestamp('created_at')->nullable();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        Schema::create('generated_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resume_id');
            $table->string('file_type'); // pdf, docx
            $table->string('file_path');
            $table->string('download_token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        Schema::create('ai_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resume_id');
            $table->string('review_type'); // grammar, ats, suggestions, missing_sections
            $table->integer('score')->nullable();
            $table->jsonb('feedback_data');
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('action'); // view, download, ai_analysis, create_resume
            $table->jsonb('details')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('job_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resume_id');
            $table->string('job_title');
            $table->text('job_description');
            $table->integer('match_score')->default(0);
            $table->jsonb('analysis_data')->nullable();
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_targets');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('ai_reviews');
        Schema::dropIfExists('generated_files');
        Schema::dropIfExists('resume_versions');
        Schema::dropIfExists('resume_sections');
        Schema::dropIfExists('resumes');
        Schema::dropIfExists('templates');
    }
};
