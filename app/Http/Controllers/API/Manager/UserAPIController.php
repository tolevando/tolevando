<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:55
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API\Manager;

use App\Criteria\Users\DriversOfMarketCriteria;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\MarketRepository;
use App\Criteria\Markets\MarketsOfUserCriteria;

class UserAPIController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;

    private $marketRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo,MarketRepository $marketRepository)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
        $this->marketRepository = $marketRepository;
    }

    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                if (!$user->hasRole('manager') && !$user->hasRole('gerente')) {
                    $this->sendError('Usuário não é dono de estabelecimento', 401);
                }

                $deviceToken = $request->input('device_token', '');
                
                if(!empty($deviceToken)){
                    //checa se o token já esta registrado
                    $tokens = explode(';|||;',$user->device_token);
                    $encontrou = false;
                    foreach($tokens as $token){
                        if($token == $deviceToken){
                            $encontrou = true;
                        }
                    }
                    if(!$encontrou){
                        $user->device_token .= ';|||;'.$deviceToken;
                    }
                }                                
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required',
            ]);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = str_random(60);
            $user->save();

            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

            event(new UserRoleChangedEvent($user));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }


        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');

    }

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        

        
 

        $settings = array_intersect_key($settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'fcm_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
            ]
        );

        
        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }


    public function getAbertoAndDelivery(Request $request){
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();        
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria($user['id']));
        $market = $this->marketRepository->orderBy('id','desc')->first();
        $settings = array();
        $settings['aberto'] = false;
        $settings['delivery'] = false;

        if($market !== null){
            $settings['aberto'] = ($market->closed==0);
            $settings['delivery'] = ($market->available_for_delivery==1);
        }

        return $this->sendResponse($settings, 'Aberto/Delivery retrieved successfully');

    }

    public function setAbertoAndDelivery(Request $request){
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();        
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria($user['id']));
        $market = $this->marketRepository->orderBy('id','desc')->all();

        $aberto = $request->input('aberto');
        $delivery = $request->input('delivery');

        foreach($market as $m){
            $m->closed = (int) (!$aberto);
            $m->available_for_delivery = (int) $delivery;
            $m->save();
            
        }

        return $this->sendResponse([], 'Sucesso');

    }

    public function checaNovoPedido(Request $request){

        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();        
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria($user['id']));
        $market = $this->marketRepository->orderBy('id','desc')->all();
        $marketIds = [];
        foreach($market as $m){
            $marketIds[] = $m->id;
        }        
        $query = "SELECT o.id FROM orders o 
        WHERE (select p.market_id from product_orders po 
        inner join products p on p.id = po.product_id 
        where po.order_id = o.id limit 1) IN (".implode(",",$marketIds).") 
        and o.active = 1 
        order by o.id desc limit 1";
        //dd($query);
        $rawQuery = \DB::select(\DB::raw($query))[0]??null;
        $retorno = array();
        if($rawQuery){
            if($rawQuery->id != $request->input('last_pedido_id')){

                $retorno['possui_novo_pedido'] = true;    
            }else{
                $retorno['possui_novo_pedido'] = false;
            }
        }else{
            $retorno['possui_novo_pedido'] = false;
        }

        return $retorno;
        
    } 

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError([
                'error' => 'Reset link not sent',
                'code' => 401,
            ], 'Reset link not sent');
        }

    }

    /**
     * Display a listing of the Drivers.
     * GET|HEAD /markets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function driversOfMarket($id, Request $request)
    {
        try{
            $this->userRepository->pushCriteria(new RequestCriteria($request));
            $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->userRepository->pushCriteria(new DriversOfMarketCriteria($id));
            $users = $this->userRepository->all();

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($users->toArray(), 'Drivers retrieved successfully');
    }
}
