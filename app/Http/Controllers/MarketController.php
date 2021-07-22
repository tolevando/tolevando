<?php
/**
 * File name: MarketController.php
 * Last modified: 2020.04.30 at 08:21:08
 * 
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\Criteria\Markets\MarketsOfUserCriteria;
use App\Criteria\Users\AdminsCriteria;
use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Criteria\Users\ManagersClientsCriteria;
use App\Criteria\Users\ManagersCriteria;
use App\DataTables\MarketDataTable;
use App\DataTables\RequestedMarketDataTable;
use App\Events\MarketChangedEvent;
use App\Http\Requests\CreateMarketRequest;
use App\Http\Requests\UpdateMarketRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\FieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Cidade;
use App\Models\Bairro;
use App\Models\OpeningHourMarket;

class MarketController extends Controller
{
    /** @var  MarketRepository */
    private $marketRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FieldRepository
     */
    private $fieldRepository;

    public function __construct(MarketRepository $marketRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo, FieldRepository $fieldRepository)
    {
        parent::__construct();
        $this->marketRepository = $marketRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepo;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Display a listing of the Market.
     *
     * @param MarketDataTable $marketDataTable
     * @return Response
     */
    public function index(MarketDataTable $marketDataTable)
    {
        return $marketDataTable->render('markets.index');
    }

    /**
     * Display a listing of the Market.
     *
     * @param MarketDataTable $marketDataTable
     * @return Response
     */
    public function requestedMarkets(RequestedMarketDataTable $requestedMarketDataTable)
    {
        return $requestedMarketDataTable->render('markets.requested');
    }

    /**
     * Show the form for creating a new Market.
     *
     * @return Response
     */
    public function create()
    {

        $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $field = $this->fieldRepository->pluck('name', 'id');
        $cidades = Cidade::where('active',1)->orderBy('uf','asc')->orderBy('cidade','asc')->get();
        $cidadesArray = [];
        foreach($cidades as $cidade){
            $cidadesArray[$cidade->uf][$cidade->id] = $cidade->cidade;
        }
        $usersSelected = [];
        $driversSelected = [];
        $fieldsSelected = [];
        $hasCustomField = in_array($this->marketRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('markets.create')->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("drivers", $drivers)->with("usersSelected", $usersSelected)->with("driversSelected", $driversSelected)->with('field', $field)->with('fieldsSelected', $fieldsSelected)->with('cidadesArray',$cidadesArray);
    }

    /**
     * Store a newly created Market in storage.
     *
     * @param CreateMarketRequest $request
     *
     * @return Response
     */
    public function store(CreateMarketRequest $request)
    {
        $input = $request->all();
        if (auth()->user()->hasRole(['gerente','cliente'])) {
            $input['users'] = [auth()->id()];
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
        try {

            //atualizar o bairro
            $bairroIds = \Request::input('bairro_id');
            $bairroAtivos = \Request::input('bairro_ativo');
            $bairroValores = \Request::input('bairro_valor');

            $bairrosJson = array();

            foreach($bairroIds??[] as $key => $bairroId){
                if(isset($bairroAtivos[$key]) && $bairroAtivos[$key] == 1){
                    $bairro = Bairro::where('id','=',$bairroId)->first();                    
                    $bairrosJson[] = [
                        'bairro_id' => $bairroId,
                        'nome' => $bairro->nome,
                        'valor' => $bairroValores[$key]
                    ];
                }
            }
            
            if(count($bairrosJson) > 0){
                $input['bairros_json'] = json_encode($bairrosJson);
            }else{
                $input['bairros_json'] = null;
            }

            unset($input['bairro_id']);

            $market = $this->marketRepository->create($input);
            $market->bairros_json = $input['bairros_json'];
            $market->cidade_id = \Request::input('cidade_id');
            $market->save();
            $market->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market, 'image');
            }
            event(new MarketChangedEvent($market, $market));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.market')]));

        return redirect(route('markets.index'));
    }

    /**
     * Display the specified Market.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show($id)
    {
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
        $market = $this->marketRepository->findWithoutFail($id);

        if (empty($market)) {
            Flash::error('Market not found');

            return redirect(route('markets.index'));
        }

        return view('markets.show')->with('market', $market);
    }

    /**
     * Show the form for editing the specified Market.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
        $market = $this->marketRepository->findWithoutFail($id);

        if (empty($market)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.market')]));
            return redirect(route('markets.index'));
        }
        if($market['active'] == 0){
            $user = $this->userRepository->getByCriteria(new ManagersClientsCriteria())->pluck('name', 'id');
        } else {
            $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        }
        //$user = $market->users();
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $field = $this->fieldRepository->pluck('name', 'id');
        $cidades = Cidade::where('active',1)->orWhere('id',$market['cidade_id'])->orderBy('uf','asc')->orderBy('cidade','asc')->get();
        $cidadesArray = [];
        foreach($cidades as $cidade){
            $cidadesArray[$cidade->uf][$cidade->id] = $cidade->cidade;
        }

        $usersSelected = $market->users()->pluck('users.id')->toArray();
        $driversSelected = $market->drivers()->pluck('users.id')->toArray();
        $fieldsSelected = $market->fields()->pluck('fields.id')->toArray();

        $customFieldsValues = $market->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
        $hasCustomField = in_array($this->marketRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('markets.edit')->with('market', $market)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("drivers", $drivers)->with("usersSelected", $usersSelected)->with("driversSelected", $driversSelected)->with('field', $field)->with('fieldsSelected', $fieldsSelected)->with('cidadesArray',$cidadesArray);
    }

    /**
     * Update the specified Market in storage.
     *
     * @param int $id
     * @param UpdateMarketRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateMarketRequest $request)
    {
        $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
        $oldMarket = $this->marketRepository->findWithoutFail($id);

        if (empty($oldMarket)) {
            Flash::error('Market not found');
            return redirect(route('markets.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
        try {
            //atualizar o bairro
            $bairroIds = \Request::input('bairro_id');
            $bairroAtivos = \Request::input('bairro_ativo');
            $bairroValores = \Request::input('bairro_valor');

            $bairrosJson = array();

            foreach($bairroIds??[] as $key => $bairroId){
                if(isset($bairroAtivos[$key]) && $bairroAtivos[$key] == 1){
                    $bairro = Bairro::where('id','=',$bairroId)->first();                    
                    $bairrosJson[] = [
                        'bairro_id' => $bairroId,
                        'nome' => $bairro->nome,
                        'valor' => $bairroValores[$key]
                    ];
                }
            }
            
            if(count($bairrosJson) > 0){
                $input['bairros_json'] = json_encode($bairrosJson);
            }else{
                $input['bairros_json'] = null;
            }

            unset($input['bairro_id']);
            //dd($input);
            $market = $this->marketRepository->update($input, $id);
            $market->bairros_json = $input['bairros_json'];
            $market->cidade_id = \Request::input('cidade_id');
            $market->save();

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $market->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new MarketChangedEvent($market, $oldMarket));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.market')]));

        return redirect(route('markets.index'));
    }

    /**
     * Remove the specified Market from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        if (!env('APP_DEMO', false)) {
            $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
            $market = $this->marketRepository->findWithoutFail($id);

            if (empty($market)) {
                Flash::error('Market not found');

                return redirect(route('markets.index'));
            }

            $this->marketRepository->delete($id);

            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.market')]));
        } else {
            Flash::warning('Esta é uma versão demo. Você não poderá fazer alterções.');
        }
        return redirect(route('markets.index'));
    }    

    /**
     * Remove Media of Market
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $market = $this->marketRepository->findWithoutFail($input['id']);
        try {
            if ($market->hasMedia($input['collection'])) {
                $market->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Retorna bairros das cidades
     * @param Request $request
     */
    public function buscaBairros(Request $request){
        $market = $this->marketRepository->findWithoutFail($request['market_id']);
        $bairros = Bairro::where('cidade_id','=',$request['cidade_id'])->get();
        $retornoArray = array();

        $bairrosJson = array();
        if($market !== null){
            $bairrosJson = json_decode($market->bairros_json,true);
        }

        foreach($bairros??[] as $bairro){
            $preenchido = false;
            $valor = '';
            foreach($bairrosJson??[] as $bairroJson){
                if($bairroJson['bairro_id'] == $bairro->id){
                    $valor = $bairroJson['valor'];
                    $preenchido = true;
                    break;
                }                
            }
            $retornoArray[] = [
                'id' => $bairro->id,
                'nome' => $bairro->nome,
                'valor' => $valor,
                'ativado' => ((int)$preenchido)

            ];
        }

        return $retornoArray;
    }

    public function saveOpeningHours($id, Request $request)
    {
        try {
            OpeningHourMarket::updateOrCreate(['market_id' => $id, 'day' => $request->day], ['open_hour' => $request->open_hour, 'close_hour' => $request->close_hour, 'open_hour_second' => $request->open_hour_second, 'close_hour_second' => $request->close_hour_second, 'automatic_open_close' => $request->automatic_open_close]);
        } catch (\Exception $e) {
            return ['statusCode' => 500, 'msg' => $e->getMessage() ];
        }

        return ['statusCode' => 200, 'msg' => 'Registro atualizado com sucesso!' ];
    }

    public function deleteOpeningHours(Request $request)
    {
        try {
            $openingHour = OpeningHourMarket::find($request->id);
            $openingHour->delete();
        } catch (\Exception $e) {
            return ['statusCode' => 500, 'msg' => $e->getMessage() ];
        }

        return ['statusCode' => 200, 'msg' => 'Registro deletado com sucesso!' ];
    }
}
