<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTemplatesTable extends Migration
{
    private string $table = 'templates';

    public function up()
    {
        Schema::table(
            $this->table,
            function (Blueprint $table) {
                if (Schema::hasColumn($this->table, 'type')) {
                    $table->renameColumn('type', 'event');
                } elseif (!Schema::hasColumn($this->table, 'event')) {
                    $table->string('event');
                }
                if (Schema::hasColumn($this->table, 'vars_set')) {
                    $table->string('vars_set')->default(null)->change();
                    $table->renameColumn('vars_set', 'channel');
                } elseif (!Schema::hasColumn($this->table, 'channel')) {
                    $table->string('channel');
                }

                $table->nullableMorphs('assignable');
                $table->boolean('default')->default(false);

                $table->dropColumn('content');
            }
        );
    }

    public function down()
    {
        Schema::table(
            $this->table,
            function (Blueprint $table) {
                if (Schema::hasColumn($this->table, 'event')) {
                    $table->renameColumn('event', 'type');
                } elseif (!Schema::hasColumn($this->table, 'type')) {
                    $table->string('type');
                }
                if (Schema::hasColumn($this->table, 'channel')) {
                    $table->renameColumn('channel', 'vars_set');
                } elseif (!Schema::hasColumn($this->table, 'vars_set')) {
                    $table->string('vars_set');
                }

                $table->longText('content');

                $table->dropColumn('default');
                $table->dropMorphs('assignable');
            }
        );
    }
}
