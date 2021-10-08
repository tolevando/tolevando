<?php
/**
 * File name: OrderAPIController.php
 * Last modified: 2020.05.31 at 19:34:40
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;


use App\Criteria\Orders\OrdersOfStatusesCriteria;
use App\Criteria\Orders\OrdersOfUserCriteria;
use App\Events\OrderChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\AssignedOrder;
use App\Notifications\NewOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CartRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductOrderRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Stripe\Token;
use App\Models\Coupon;
use App\Models\Option;
use App\Models\Product;
use App\Models\Market;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  ProductOrderRepository */
    private $productOrderRepository;
    /** @var  CartRepository */
    private $cartRepository;
    /** @var  UserRepository */
    private $userRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;

    /**
     * OrderAPIController constructor.
     * @param OrderRepository $orderRepo
     * @param ProductOrderRepository $productOrderRepository
     * @param CartRepository $cartRepo
     * @param PaymentRepository $paymentRepo
     * @param NotificationRepository $notificationRepo
     * @param UserRepository $userRepository
     */
    public function __construct(OrderRepository $orderRepo, ProductOrderRepository $productOrderRepository, CartRepository $cartRepo, PaymentRepository $paymentRepo, NotificationRepository $notificationRepo, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepo;
        $this->productOrderRepository = $productOrderRepository;
        $this->cartRepository = $cartRepo;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepo;
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->orderRepository->pushCriteria(new RequestCriteria($request));
            $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfStatusesCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $orders = $this->orderRepository->orderBy('id','desc')->all();

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    /**
     * Display the specified Order.
     * GET|HEAD /orders/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Order $order */
        if (!empty($this->orderRepository)) {
            try {
                $this->orderRepository->pushCriteria(new RequestCriteria($request));
                $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $order = $this->orderRepository->findWithoutFail($id);
        }

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');


    }

    /**
     * Store a newly created Order in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $payment = $request->only('payment');
        if (isset($payment['payment']) && $payment['payment']['method']) {
            if ($payment['payment']['method'] == "Credit Card (Stripe Gateway)") {
                return $this->stripPayment($request);
            } elseif ($payment['payment']['method'] == "Credit Card (Pagarme Gateway)") {
                return $this->pagarmePayment($request);
            }else {
                return $this->cashPayment($request);

            }
        }
    }


    private function pagarmePayment(Request $request){
        $input = $request->all();
        $amount = 0;
        try {
            $pagarme = new \PagarMe\Client(setting('pagarme_key'));
            //fazer a cobrança            
            $market = null;

            $itensArray = [];
            $somaParcial = 0;
            foreach ($input['products'] as $productOrder) {
                //$productOrder['order_id'] = $order->id;                
                $product = Product::where('id','=',$productOrder['product_id'])->firstOrFail();
                $market = Market::where('id','=',$product['market_id'])->firstOrFail();;                
                foreach($productOrder['options'] as $keyOption => $valueOption){
                    
                    $option = Option::where('id','=',$valueOption)->first();
                    $productOrder['price'] += $option->price??0;

                }


                $unityPriceWithDiscount = $productOrder['price'];

                if(isset($input['coupon_id']) && !empty($input['coupon_id'])){
                    $coupon = Coupon::where('id',$input['coupon_id'])->first();
                    if(!is_null($coupon)){
                        $unityPriceWithDiscount = $unityPriceWithDiscount-(($coupon->discount*$unityPriceWithDiscount)/100);                        
                        if($amount < 0){
                            $amount = 0;
                        }
                    }
                }

                $itensArray[] = [
                    'id' => "p-".$product->id,
                    'title' => substr($product->name,0,30),
                    'unit_price' => retornaPagarmeFormatFromDecimal($unityPriceWithDiscount),
                    'quantity' => intval($productOrder['quantity']),
                    'tangible' => true
                ];

                $somaParcial += $unityPriceWithDiscount*$productOrder['quantity'];
                $amount += $productOrder['price'] * $productOrder['quantity'];

                //$this->productOrderRepository->create($productOrder);
            }
            
            if(isset($input['coupon_id']) && !empty($input['coupon_id'])){
                $coupon = Coupon::where('id',$input['coupon_id'])->first();
                if(!is_null($coupon)){
                    if ($coupon->discount_type == "fixed") {
                        $amount -= $coupon->discount;
                    } else {
                        $amount = $amount-(($coupon->discount*$amount)/100);
                    }
                    if($amount < 0){
                        $amount = 0;
                    }
                }
            }

            if(!isset($input['delivery_address_id']) || (isset($input['delivery_address_id']) && (empty($input['delivery_address_id']) || is_null($input['delivery_address_id']) || $input['delivery_address_id']==0))){
                $input['delivery_fee'] = 0.00;
            }

            $normalAmount = $amount;
            $amount += $input['delivery_fee'];
            $amountWithTax = $amount + ($amount * $input['tax'] / 100);

            $diferenca = $amountWithTax - $somaParcial;
            if(intval(retornaPagarmeFormatFromDecimal($diferenca)) > 0){
                $itens[] = [
                    'id' => "fees",
                    'title' => "Taxas Delivery",
                    'unit_price' => retornaPagarmeFormatFromDecimal($diferenca),
                    'quantity' => 1,
                    'tangible' => false
                ];
            }
            

            $user = $this->userRepository->findWithoutFail($input['user_id']);
            if (empty($user)) {
                return $this->sendError('User not found');
            }
            
            $pagarmeArray = [
                'async' => false,
                'amount' => retornaPagarmeFormatFromDecimal($amountWithTax),
                'payment_method' => 'credit_card',
                'card_holder_name' => $input['pagarme_name'],
                'card_cvv' => $input['pagarme_cvc'],
                'card_number' => $input['pagarme_number'],
                'card_expiration_date' => $input['pagarme_exp_month'].$input['pagarme_exp_year'],
                'customer' => [
                  'external_id' => $input['user_id'],
                  'name' => $input['nome'],
                  'type' => 'individual',
                  'country' => 'br',
                  'documents' => [
                    [
                      'type' => 'cpf',
                      'number' => $input['cpf']
                    ]
                  ],                  
                  'email' => $user->email,
                  'phone_numbers' => ["+5511999998888"]
                ],
                'billing' => [
                  'name' => $input['nome'],
                  'address' => [
                    'country' => 'br',
                    'street' => $input['endereco_endereco'],
                    'street_number' => $input['endereco_numero'],
                    'state' => $input['endereco_uf'],
                    'city' => $input['endereco_cidade'],
                    'neighborhood' => $input['endereco_bairro'],
                    'zipcode' => $input['endereco_cep']
                  ]
                ],  
                'items' => $itensArray                              
            ];
            
            if($market->pagarme_ativado){
                $valorEstabelecimento = ($normalAmount*$market->admin_commission/100);

                if($market->pagarme_recebedor_taxa_entrega){
                    $valorEstabelecimento += $input['delivery_fee'];
                }
                $valorApp = $amount-$valorEstabelecimento;

                $pagarmeArray['split_rules'] = [
                    [
                      'amount' => retornaPagarmeFormatFromDecimal($valorApp),
                      'recipient_id' => setting('pagarme_recipient_id'),
                      'charge_processing_fee' => true,
                      'liable' => true
                    ],
                    [
                      'amount' => retornaPagarmeFormatFromDecimal($valorEstabelecimento),
                      'recipient_id' => $market->pagarme_recipient_id,
                      'charge_processing_fee' => true,
                      'liable' => true
                    ]
                ];

            }

            



            //$market = $order->productOrders[0]->product->market;

            $transaction = $pagarme->transactions()->create($pagarmeArray);

            /*ob_flush();
            ob_start();
            var_dump($pagarmeArray);
            var_dump($transaction);
            
            file_put_contents("/tmp/dump.txt", ob_get_flush());*/

            if($transaction->status !== "paid"){
                //transação foi recusada
                $retorno = [
                    'sucesso' => 0,
                    'mensagem' => 'A transação foi recusada no cartão'
                ];                
                return $retorno;

            }else{
                $amount = 0;
                //transacao foi aceita e já paga pelo cliente
                if (empty($input['delivery_address_id'])) {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'hint','observacao','troco_para','bairro_id','coupon_id','data_hora')
                    );
                } else {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee', 'hint','observacao','troco_para','bairro_id','coupon_id','data_hora')
                    );
                }
                //Log::info($input['products']);
                foreach ($input['products'] as $productOrder) {
                    $productOrder['order_id'] = $order->id;                

                    foreach($productOrder['options'] as $keyOption => $valueOption){
                        
                        $option = Option::where('id','=',$valueOption)->first();
                        $productOrder['price'] += $option->price??0;

                    }
                    $amount += $productOrder['price'] * $productOrder['quantity'];

                    $this->productOrderRepository->create($productOrder);
                }
                if(isset($order->coupon_id) && !empty($order->coupon_id)){
                    $coupon = Coupon::where('id',$order->coupon_id)->first();
                    if(!is_null($coupon)){
                        if ($coupon->discount_type == "fixed") {
                            $amount -= $coupon->discount;
                        } else {
                            $amount = $amount-(($coupon->discount*$amount)/100);
                        }
                        if($amount < 0){
                            $amount = 0;
                        }
                    }
                }
                $amount += $order->delivery_fee;
                $amountWithTax = $amount + ($amount * $order->tax / 100);

                


                $payment = $this->paymentRepository->create([
                    "user_id" => $input['user_id'],
                    "description" => "Pagamento Concluído",
                    "price" => $amountWithTax,
                    "status" => 'Pago',
                    "method" => $input['payment']['method'],
                ]);

                $this->orderRepository->update(['payment_id' => $payment->id], $order->id);

                $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);
                $usersMarket = $order->productOrders[0]->product->market->users;
                $userNotifications = array();
                foreach($usersMarket as $userMarket){
                    $deviceTokens = explode(';|||;',$userMarket->device_token);
                    foreach($deviceTokens as $deviceToken){
                        $newUserMarket = clone $userMarket;
                        $newUserMarket->device_token = $deviceToken;
                        $userNotifications[] = $newUserMarket;
                    }
                }
                    
                Notification::send($userNotifications, new NewOrder($order));
                $retorno = array();                
                $retorno['sucesso'] = 1;
                $retorno['data'] = $order->toArray();
                return $retorno;
            }                             
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function stripPayment(Request $request)
    {
        $input = $request->all();
        $amount = 0;
        try {
            $user = $this->userRepository->findWithoutFail($input['user_id']);
            if (empty($user)) {
                return $this->sendError('User not found');
            }
            $stripeToken = Token::create(array(
                "card" => array(
                    "number" => $input['stripe_number'],
                    "exp_month" => $input['stripe_exp_month'],
                    "exp_year" => $input['stripe_exp_year'],
                    "cvc" => $input['stripe_cvc'],
                    "name" => $user->name,
                )
            ));
            if ($stripeToken->created > 0) {
                if (empty($input['delivery_address_id'])) {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'hint','data_hora')
                    );
                } else {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee', 'hint','data_hora')
                    );
                }
                foreach ($input['products'] as $productOrder) {
                    $productOrder['order_id'] = $order->id;
                    $amount += $productOrder['price'] * $productOrder['quantity'];
                    $this->productOrderRepository->create($productOrder);
                }
                $amount += $order->delivery_fee;
                $amountWithTax = $amount + ($amount * $order->tax / 100);
                $charge = $user->charge((int)($amountWithTax * 100), ['source' => $stripeToken]);
                $payment = $this->paymentRepository->create([
                    "user_id" => $input['user_id'],
                    "description" => trans("lang.payment_order_done"),
                    "price" => $amountWithTax,
                    "status" => $charge->status, // $charge->status
                    "method" => $input['payment']['method'],
                ]);
                $this->orderRepository->update(['payment_id' => $payment->id], $order->id);

                $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

                Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        $retorno = array();                
        $retorno['sucesso'] = 1;
        $retorno['data'] = $order->toArray();
        return $retorno;
        //return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function cashPayment(Request $request)
    {
        $input = $request->all();
        $amount = 0;
        try {
            $order = $this->orderRepository->create(
                $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee', 'hint','observacao','troco_para','bairro_id','coupon_id','data_hora', 'card_brand')
            );
            //Log::info($input['products']);
            foreach ($input['products'] as $productOrder) {
                $productOrder['order_id'] = $order->id;                

                $optionMidPizza = Product::find($productOrder['product_id'])->option_mid_pizza;      
                $firstOptionValue =  0;
                $counter = 0;         
                foreach($productOrder['options'] as $keyOption => $valueOption){
                    $option = Option::where('id','=',$valueOption)->first();

                    switch ($optionMidPizza) {
                        case 0:
                            $productOrder['price'] += $option->price??0;
                            break;
                        case 1:
                            $counter += 1;

                            if ($counter < 3) {
                                $productOrder['price'] += $option->price ? ($option->price / 2) : 0;
                            }else {
                                $productOrder['price'] += $option->price??0;
                            }

                            break;
                        case 2:
                            if ($counter == 0) {
                                $firstOptionValue = $option->price??0;
                            }

                            $counter += 1;

                            if ($counter < 3) {
                                $productOrder['price'] = (($firstOptionValue > $option->price) ? $firstOptionValue : $option->price);
                            }else {
                                $productOrder['price'] += $option->price??0;
                            }

                            break;
                        
                        default:
                            $productOrder['price'] += $option->price??0;
                            break;
                    }
                }
                $amount += $productOrder['price'] * $productOrder['quantity'];

                $this->productOrderRepository->create($productOrder);
            }
            if(isset($order->coupon_id) && !empty($order->coupon_id)){
                $coupon = Coupon::where('id',$order->coupon_id)->first();
                if(!is_null($coupon)){
                    if ($coupon->discount_type == "fixed") {
                        $amount -= $coupon->discount;
                    } else {
                        $amount = $amount-(($coupon->discount*$amount)/100);
                    }
                    if($amount < 0){
                        $amount = 0;
                    }
                }
            }

            $amount += $order->delivery_fee;
            $amountWithTax = $amount + ($amount * $order->tax / 100);
            $payment = $this->paymentRepository->create([
                "user_id" => $input['user_id'],
                "description" => trans("lang.payment_order_waiting"),
                "price" => $amountWithTax,
                "status" => 'Aguardando o Cliente',
                "method" => $input['payment']['method'],
            ]);

            $this->orderRepository->update(['payment_id' => $payment->id], $order->id);
            $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

            $usersMarket = $order->productOrders[0]->product->market->users;
            $userNotifications = array();
            foreach($usersMarket as $userMarket){
                $deviceTokens = explode(';|||;',$userMarket->device_token);
                foreach($deviceTokens as $deviceToken){
                    $newUserMarket = clone $userMarket;
                    $newUserMarket->device_token = $deviceToken;
                    $userNotifications[] = $newUserMarket;
                }
            }
                
            Notification::send($userNotifications, new NewOrder($order));
            
            

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        $retorno = array();                
        $retorno['sucesso'] = 1;
        $retorno['data'] = $order->toArray();
        return $retorno;
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        if (empty($oldOrder)) {
            return $this->sendError('Order not found');
        }
        $oldStatus = $oldOrder->payment->status;
        $input = $request->all();

        try {
            $order = $this->orderRepository->update($input, $id);
            if (isset($input['order_status_id']) && $input['order_status_id'] == 5 && !empty($order)) {
                $this->paymentRepository->update(['description' => 'Pagamento Concluído','status' => 'Pago'], $order['payment_id']);
            }
            event(new OrderChangedEvent($oldStatus, $order));

            if (setting('enable_notifications', false)) {
                if (isset($input['order_status_id']) && $input['order_status_id'] != $oldOrder->order_status_id) {
                    Notification::send([$order->user], new StatusChangedOrder($order));
                }

                if (isset($input['driver_id']) && ($input['driver_id'] != $oldOrder['driver_id'])) {
                    $driver = $this->userRepository->findWithoutFail($input['driver_id']);
                    if (!empty($driver)) {
                        Notification::send([$driver], new AssignedOrder($order));
                    }
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

}
