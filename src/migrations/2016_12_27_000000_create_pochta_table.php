<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 *
 * @version 2016-12-27
 *
 * @since   1.0
 */
namespace Helldar\Pochta;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pochta
{
    protected $table_name = 'pochta';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string('track_id')->unique();
            $table->longText('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table_name);
    }
}
