<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MarketReview
 * @package App\Models
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @property \App\Models\User user
 * @property \App\Models\Market market
 * @property string review
 * @property unsignedTinyInteger rate
 * @property integer user_id
 * @property integer market_id
 */
class OpeningHourMarket extends Model
{

    public $table = 'opening_hour_markets';
    
    public $fillable = [
        'day',
        'open_hour',
        'close_hour',
        'automatic_open_close',
        'market_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }
    
}
