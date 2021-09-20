<?php

use EscolaLms\Courses\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    private string $table = 'templates';

    public function up()
    {
        Schema::create(
            $this->table,
            function (Blueprint $table) {
                $table->id('id');
                $table->string('name');
                $table->string('type');
                $table->foreignIdFor(Course::class, 'cours_id')->nullable();
                $table->longText('content');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
