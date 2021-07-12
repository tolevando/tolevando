<?php
/**
 * File name: ProductsOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\Products;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ProductsOfUserCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductsOfUserCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * ProductsOfUserCriteria constructor.
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
            return $model->join('user_markets', 'user_markets.market_id', '=', 'products.market_id')
                ->groupBy('products.id')
                ->select('products.*')
                ->where('user_markets.user_id', $this->userId);
        } elseif (auth()->user()->hasRole('entregador')) {
            return $model->join('driver_markets', 'driver_markets.market_id', '=', 'products.market_id')
                ->groupBy('products.id')
                ->select('products.*')
                ->where('driver_markets.user_id', $this->userId);
        } else {
            return $model;
        }
    }
}
