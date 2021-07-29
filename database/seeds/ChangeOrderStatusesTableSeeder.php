<?php

use Illuminate\Database\Seeder;

class ChangeOrderStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_statuses')->where('status', 'Pronto para o Envio')->delete();
        \DB::table('order_statuses')->where('status', 'Entregue')->delete();
    }
}