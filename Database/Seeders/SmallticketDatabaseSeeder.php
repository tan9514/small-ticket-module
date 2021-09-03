<?php

namespace Modules\Smallticket\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SmallticketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
            SmallTicketAuthMenuSeeder::class,
            SmallTicketSettingSeeder::class
        ]);
    }
}
