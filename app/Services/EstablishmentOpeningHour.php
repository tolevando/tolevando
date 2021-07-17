<?php

namespace App\Services;
use App\Models\OpeningHourMarket;
use App\Models\Market;
use Carbon\Carbon;

class EstablishmentOpeningHour {

    public static function checkAutomaticOpenHour()
    {
        $markets = Market::all();

        $date_actual = Carbon::now()->settings(['locale' => 'pt_BR']);

        $day = ucfirst($date_actual->dayName);

        foreach ($markets as $k => $market) {

            if (isset($market->openingHourMarket) && count($market->openingHourMarket)) {
                foreach ($market->openingHourMarket as $key => $hour) {

                    if ($hour->day == $day && $hour->automatic_open_close) {
                        if ($hour->open_hour < $date_actual->format('H:i:s')) {
                            $market->closed = 0;
                            $market->save();
                        }

                        if ($hour->close_hour < $date_actual->format('H:i:s')) {
                            $market->closed = 1;
                            $market->save();
                        }
                    }
                }

            }
        }
    }
}