<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // 1. Hubi in Column-ku uu jiro, haddii kale dhis
    if (!Schema::hasColumn('attendances', 'course_id')) {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->after('user_id')->nullable();
        });
    }

    // 2. Hadda oo aan ognahay in xogtu ay leedahay ID 1 (tallaabadii SQL), 
    // ayaan dhisaynaa Foreign Key-ga
    Schema::table('attendances', function (Blueprint $table) {
        $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropForeign(['course_id']);
        $table->dropColumn('course_id');
    });
}
};
