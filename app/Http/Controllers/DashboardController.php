<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Criteria\Markets\MarketsOfUserCriteria;
use App\Repositories\MarketRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /** @var  OrderRepository */
    private $orderRepository;


    /**
     * @var UserRepository
     */
    private $userRepository;

    /** @var  MarketRepository */
    private $marketRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(OrderRepository $orderRepo, UserRepository $userRepo, PaymentRepository $paymentRepo, MarketRepository $marketRepo)
    {
        parent::__construct();
        $this->orderRepository = $orderRepo;
        $this->userRepository = $userRepo;
        $this->marketRepository = $marketRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset($_GET['action']) && $_GET['action'] == 'ajaxAlteraEstadoEstabelecimento'){
            return $this->ajaxAlteraEstadoEstabelecimento();
        }elseif(isset($_GET['action']) && $_GET['action'] == 'ajaxRetornaNumerosDashboardEstabelecimento'){
            return $this->ajaxRetornaNumerosDashboardEstabelecimento();
        }elseif(isset($_GET['action']) && $_GET['action'] == 'ajaxBuscaUltimosPedidos'){
            return $this->ajaxBuscaUltimosPedidos();
        }
//        dd($ajaxEarningUrl);

        if (auth()->user()->hasRole('admin')) {
            $ordersCount = $this->orderRepository->where('active','1')->count();
            $membersCount = $this->userRepository->count();
            $marketsCount = $this->marketRepository->count();
            $markets = $this->marketRepository->limit(4)->get();
            /*$earning = \DB::table('orders')->selectRaw("sum(payments.price) as price")
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')
            ->leftJoin('products', 'products.id', '=', 'product_orders.product_id')
            ->whereRaw('orders.active = 1')
            ->get()->first();*/
            $earning = $this->paymentRepository->join("orders", "payments.id", "=", "orders.payment_id")->where('orders.active','1')->where('payments.status','!=','Não Pago')->select('payments.*')->sum('price');

            $ajaxEarningUrl = route('payments.byMonth',['api_token'=>auth()->user()->api_token]);
            return view('dashboard.index')
                ->with("ajaxEarningUrl", $ajaxEarningUrl)
                ->with("ordersCount", $ordersCount)
                ->with("marketsCount", $marketsCount)
                ->with("markets", $markets)
                ->with("membersCount", $membersCount)
                ->with("earning", $earning);
        }else{
            //estabelecimento        
            
            

            $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
            $market = $this->marketRepository->orderBy('id','desc')->first();

            return view('dashboard.estabelecimento',[
                'market' => $market
            ]);
        }
    }
    public function ajaxBuscaUltimosPedidos(){
        $retorno = array();
        $pedidos = $this->orderRepository->newQuery()->with("user")->with("orderStatus")->with('payment')
        ->join("product_orders", "orders.id", "=", "product_orders.order_id")
        ->join("products", "products.id", "=", "product_orders.product_id")
        ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
        ->where('user_markets.user_id', auth()->id())
        ->groupBy('orders.id')
        ->orderBy('orders.created_at','DESC')
        ->select('orders.*')->get();
        //dd($pedidos);
        foreach($pedidos as $pedido){  
            $produtos = "";
            foreach($pedido->products as $product){
                $produtos = $product->name.", ";
            }

            $produtos = (strlen($produtos)>3)?substr($produtos,0,strlen($produtos)-2):$produtos;            
            $retorno[] = [
                'id' => $pedido->id,
                'data' => (new \Carbon\Carbon($pedido->created_at))->format('d/m/Y H:i:s'),
                'cliente' => $pedido->user->name,
                'status_id' => $pedido->order_status_id,
                'status' => (($pedido->active=="1")?$pedido->orderStatus->status:"<label style='color:red'>Pedido Cancelado</label>"),
                'metodo_pagamento' => $pedido->payment->method,
                'produtos' => $produtos,
                'status_pagamento' => $pedido->payment->status,
                'valor' => $pedido->payment->price
            ];

            
        }
        return $retorno;
        

    }
    public function ajaxRetornaNumerosDashboardEstabelecimento(){
        $retorno = array();

        if (auth()->user()->hasRole('gerente')) {
            $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
            $markets = $this->marketRepository->orderBy('id','desc')->all();
            $ids = [0];
            foreach($markets as $market){
                $ids[] = $market->id;
            }

            $pedidosHoje = \DB::table('orders')->selectRaw("count(distinct(orders.id)) as qtde")
            ->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')
            ->leftJoin('products', 'products.id', '=', 'product_orders.product_id')
            ->whereRaw('orders.active = 1 and products.market_id IN ('.implode(",",$ids).') and orders.created_at between "'.date('Y-m-d')." 00:00:00".'" and "'.date('Y-m-d')." 23:59:59".'" ')
            ->get()->first();
            $pedidosHoje = $pedidosHoje->qtde;

            $ganhosHoje = \DB::table('orders')->selectRaw("count(distinct(orders.id)),sum(payments.price) as qtde")
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')
            ->leftJoin('products', 'products.id', '=', 'product_orders.product_id')
            ->whereRaw('orders.active = 1 and products.market_id IN ('.implode(",",$ids).') and orders.created_at between "'.date('Y-m-d')." 00:00:00".'" and "'.date('Y-m-d')." 23:59:59".'" ')
            ->get()->first();
            $ganhosHoje = $ganhosHoje->qtde;

            $numeroClientes = \DB::table('orders')->selectRaw("count(distinct(orders.user_id)) as qtde")
            ->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')
            ->leftJoin('products', 'products.id', '=', 'product_orders.product_id')
            ->whereRaw('orders.active = 1 and products.market_id IN ('.implode(",",$ids).') and orders.created_at between "'.date('Y-m-d')." 00:00:00".'" and "'.date('Y-m-d')." 23:59:59".'" ')
            ->get()->first();
            $numeroClientes = $numeroClientes->qtde;

            //$this->orderRepository->whereBetween('created_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"])->count();
            //$ganhosHoje = $this->paymentRepository->whereBetween('created_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"])->get()->sum('price');
            //$numeroClientes = $this->orderRepository->select('user_id')->count();


            return [
                'success' => true,
                'pedidosHoje' => $pedidosHoje,
                'ganhosHoje' => number_format($ganhosHoje,2,',','.'),
                'numeroClientes' => $numeroClientes        
            ];
        }else{
            return ['error' => 'Sem permissões para esta consulta'];
        }

        

    }

    public function ajaxAlteraEstadoEstabelecimento(){
        if (auth()->user()->hasRole('gerente')) {
            //$request = \Request::all();
            if(!(isset($_GET['aberto']) && isset($_GET['delivery']))){
                return ['success' => 0];
            }
            $aberto = 0;
            $delivery = 0;
            if(($_GET['aberto']??'N') == 'S'){
                $aberto = 1;
            }
            if(($_GET['delivery']??'N') == 'S'){
                $delivery = 1;
            }
            
            $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
            $market = $this->marketRepository->orderBy('id','desc')->first();
            // $markets = $this->marketRepository->orderBy('id','desc')->get();

            // foreach ($markets as $key => $market) {
            $market->closed = !$aberto;
            $market->available_for_delivery = $delivery;
            $market->save();
            // }
            
            return ['success' => 1];        
        }
    }

}
