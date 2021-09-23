<?php

use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;
use EscolaLms\Templates\Models\Template;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    private string $table = 'certificates';

    public function up()
    {
        Schema::create(
            $this->table,
            function (Blueprint $table) {
                $table->id('id');
                $table->string('path')->nullable();
                $table->enum('status', ['pending', 'processing', 'finished', 'failed'])->default('pending');
                $table->foreignIdFor(Course::class, 'course_id');
                $table->foreignIdFor(User::class, 'user_id');
                $table->foreignIdFor(Template::class, 'template_id');
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
