<?php
/**
 * File name: MarketsOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\Markets;

use App\Models\User;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MarketsOfUserCriteria.
 *
 * @package namespace App\Criteria\Markets;
 */
class MarketsOfUserCriteria implements CriteriaInterface
{

    /**
     * @var User
     */
    private $userId;

    /**
     * MarketsOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (auth()->user()->hasRole('admin')) {
            return $model;
        } elseif (auth()->user()->hasRole('gerente')) {
            return $model->join('user_markets', 'user_markets.market_id', '=', 'markets.id')
                ->select('markets.*')
                ->where('user_markets.user_id', $this->userId);
        } elseif (auth()->user()->hasRole('entregador')) {
            return $model->join('driver_markets', 'driver_markets.market_id', '=', 'markets.id')
                ->select('markets.*')
                ->where('driver_markets.user_id', $this->userId);
        } else {
            return $model;
        }
    }
}
