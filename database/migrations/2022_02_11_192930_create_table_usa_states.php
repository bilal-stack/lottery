<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsaStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usa_states', function (Blueprint $table) {
            $table->id();
            $table->char('code', 2);
            $table->string('name');
            $table->timestamps();
        });

        DB::statement("
        INSERT INTO usa_states (code, name)
            VALUES
            ('dc','District of Columbia'),
            ('al','Alabama'),
            ('ak','Alaska'),
            ('az','Arizona'),
            ('ar','Arkansas'),
            ('ca','California'),
            ('co','Colorado'),
            ('ct','Connecticut'),
            ('de','Delaware'),
            ('fl','Florida'),
            ('ga','Georgia'),
            ('hi','Hawaii'),
            ('id','Idaho'),
            ('il','Illinois'),
            ('in','Indiana'),
            ('ia','Iowa'),
            ('ks','Kansas'),
            ('ky','Kentucky'),
            ('la','Louisiana'),
            ('me','Maine'),
            ('md','Maryland'),
            ('ma','Massachusetts'),
            ('mi','Michigan'),
            ('mn','Minnesota'),
            ('ms','Mississippi'),
            ('mo','Missouri'),
            ('mt','Montana'),
            ('ne','Nebraska'),
            ('nv','Nevada'),
            ('nh','New Hampshire'),
            ('nj','New Jersey'),
            ('nm','New Mexico'),
            ('ny','New York'),
            ('nc','North Carolina'),
            ('nd','North Dakota'),
            ('oh','Ohio'),
            ('ok','Oklahoma'),
            ('or','Oregon'),
            ('pa','Pennsylvania'),
            ('pr','Puerto Rico'),
            ('ri','Rhode Island'),
            ('sc','South Carolina'),
            ('sd','South Dakota'),
            ('tn','Tennessee'),
            ('tx','Texas'),
            ('ut','Utah'),
            ('vt','Vermont'),
            ('va','Virginia'),
            ('vi','Virgin Islands'),
            ('wa','Washington'),
            ('wv','West Virginia'),
            ('wi','Wisconsin'),
            ('wy','Wyoming');
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usa_states');
    }
}
