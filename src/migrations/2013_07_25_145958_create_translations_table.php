<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translator_translations', function($table){
            $table->increments('id');
            $table->string('locale', 10)->index();
            $table->string('namespace', 150)->default('*')->index();
            $table->string('group', 150)->index();
            $table->string('item', 150)->index();
            $table->text('text');
            $table->boolean('unstable')->default(false)->index();
            $table->boolean('locked')->default(false)->index();
            $table->timestamps();
            $table->foreign('locale')->references('locale')->on('translator_languages')->onUpdate('cascade');
            $table->unique(['locale', 'namespace', 'group', 'item']);
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translator_translations');
    }

}
