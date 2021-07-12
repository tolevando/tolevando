<?php
/**
 * File name: OptionsOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\Options;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OptionsOfUserCriteria.
 *
 * @package namespace App\Criteria\Options;
 */
class OptionsOfUserCriteria implements CriteriaInterface
{

    /**
     * @var User
     */
    private $userId;

    /**
     * OptionsOfUserCriteria constructor.
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
            return $model->join('products', 'options.product_id', '=', 'products.id')
                ->join('user_markets', 'user_markets.market_id', '=', 'products.market_id')
                ->groupBy('options.id')
                ->select('options.*')
                ->where('user_markets.user_id', $this->userId);
        } elseif (auth()->user()->hasRole('entregador')) {
            return $model->join('products', 'options.product_id', '=', 'products.id')
                ->join('driver_markets', 'driver_markets.market_id', '=', 'products.market_id')
                ->groupBy('options.id')
                ->select('options.*')
                ->where('driver_markets.user_id', $this->userId);
        } else {
            return $model;
        }
    }
}
