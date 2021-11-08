<?php

namespace App\Repositories;

use App\Models\BrandMarket;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class BrandMarketRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method BrandMarket findWithoutFail($id, $columns = ['*'])
 * @method BrandMarket find($id, $columns = ['*'])
 * @method BrandMarket first($columns = ['*'])
 */
class BrandMarketRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'brand',
        'market_id'     
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BrandMarket::class;
    }

}
