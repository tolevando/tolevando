<?php
/**
 * File name: Market.php
 * Last modified: 2020.06.07 at 07:02:57
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Market
 * @package App\Models
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection Product
 * @property \Illuminate\Database\Eloquent\Collection Gallery
 * @property \Illuminate\Database\Eloquent\Collection MarketsReview
 * @property \Illuminate\Database\Eloquent\Collection[] discountables
 * @property \Illuminate\Database\Eloquent\Collection[] fields
 * @property \Illuminate\Database\Eloquent\Collection[] User
 * @property \Illuminate\Database\Eloquent\Collection[] Market
 * @property string name
 * @property string description
 * @property string address
 * @property string latitude
 * @property string longitude
 * @property string phone
 * @property string mobile
 * @property string information
 * @property double admin_commission
 * @property double admin_monthly
 * @property double delivery_fee
 * @property double default_tax
 * @property double delivery_range
 * @property boolean available_for_delivery
 * @property boolean closed
 * @property boolean active
 * @property boolean exclusive
 * @property date fidelity_at
 * @property date bonus_at
 * @property string type_of_plan
 */
class Market extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'markets';
    

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    public $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'mobile',
        'admin_commission',
        'admin_monthly',
        'delivery_fee',
        'bairro_id',
        'default_tax',
        'delivery_range',
        'available_for_delivery',
        'offline_payment_option_cash',
        'offline_payment_option_credit',
        'offline_payment_option_debit',
        'pagarme_ativado',
        'pagarme_recipient_id',
        'pagarme_recebedor_taxa_entrega',
        'exige_agendamento',
        'closed',
        'information',
        'active',
        'exclusive',
        'fidelity_at',
        'bonus_at',
        'type_of_plan',
        'estimated_time_get_product',
        'estimated_time_delivery',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'image' => 'string',
        'address' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'phone' => 'string',
        'mobile' => 'string',
        'admin_commission' =>'double',
        'admin_monthly' =>'double',
        'delivery_fee'=>'double',
        'default_tax'=>'double',
        'delivery_range'=>'double',
        'available_for_delivery'=>'boolean',
        'closed'=>'boolean',
        'information' => 'string',
        'active' =>'boolean',
        'exclusive' =>'boolean',
        'offline_payment_option_cash' =>'boolean',
        'offline_payment_option_credit' =>'boolean',
        'offline_payment_option_debit' =>'boolean',
        'pagarme_ativado' => 'boolean',
        'pagarme_recipient_id' => 'string',
        'pagarme_recebedor_taxa_entrega' => 'boolean',
        'exige_agendamento' => 'boolean',
        'fidelity_at' => 'string',
        'bonus_at' => 'string',
        'type_of_plan' => 'string'
        'estimated_time_get_product' => 'string',
        'estimated_time_delivery' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $adminRules = [
        'name' => 'required',
        'description' => 'required',
        'delivery_fee' => 'nullable|numeric|min:0',
        'address' => 'required',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
        'admin_commission' => 'required|numeric|min:0',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $managerRules = [
        'name' => 'required',
        'description' => 'required',
        'delivery_fee' => 'nullable|numeric|min:0',
        'address' => 'required',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media',
        'rate'
        
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default',$conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension,config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName,$conversion));
        }else{
            return asset(config('medialibrary.icons_folder').'/'.$extension.'.png');
        }
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getRateAttribute()
    {
        return $this->marketReviews()->select(DB::raw('round(AVG(market_reviews.rate),1) as rate'))->first('rate')->rate;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'market_id');
    }    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function openingHourMarket()
    {
        return $this->hasMany(\App\Models\OpeningHourMarket::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function brandMarket()
    {
        return $this->hasMany(\App\Models\BrandMarket::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function galleries()
    {
        return $this->hasMany(\App\Models\Gallery::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function marketReviews()
    {
        return $this->hasMany(\App\Models\MarketReview::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_markets');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function drivers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'driver_markets');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function fields()
    {
        return $this->belongsToMany(\App\Models\Field::class, 'market_fields');
    }

    
}
