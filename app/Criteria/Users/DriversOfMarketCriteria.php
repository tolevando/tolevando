<?php
/**
 * File name: DriversOfMarketCriteria.php
 * Last modified: 2020.04.30 at 07:49:44
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\Users;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class DriversOfMarketCriteria.
 *
 * @package namespace App\Criteria\Users;
 */
class DriversOfMarketCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $marketId;

    private $orDriverId = 0;

    /**
     * DriversOfMarketCriteria constructor.
     */
    public function __construct(int $marketId,$orDriverId = 0)
    {
        $this->marketId = $marketId;
        $this->orDriverId = $orDriverId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $userId = $this->orDriverId;
        $r = $model->select('users.id','users.name')->join('driver_markets','users.id','=','driver_markets.user_id')
            ->join('drivers','users.id','drivers.user_id')
            ->where(function($q) use ($userId){
                $q->where('drivers.available','=','1')
                ->orWhere('drivers.user_id','=',$userId);
            })        
            ->where('driver_markets.market_id',$this->marketId);

            return $r;
    }
}
