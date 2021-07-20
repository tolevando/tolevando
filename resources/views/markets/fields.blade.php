@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif

<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.market_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_name_help") }}
            </div>
        </div>
    </div>

    <!-- fields Field -->
    <div class="form-group row">
        {!! Form::label('cidade_id', "Cidade",['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('cidade_id', ['' => 'Selecione a cidade']+$cidadesArray, $market->cidade_id??null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">Selecione a cidade do estabelecimento</div>
        </div>
    </div>

    <!-- fields Field -->
    <div class="form-group row ">
        {!! Form::label('fields[]', trans("lang.market_fields"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('fields[]', $field, $fieldsSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.market_fields_help") }}</div>
        </div>
    </div>

    @hasanyrole('admin|gerente')
    <!-- Users Field -->
    <div class="form-group row ">
        {!! Form::label('drivers[]', trans("lang.market_drivers"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('drivers[]', $drivers, $driversSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.market_drivers_help") }}</div>
        </div>
    </div>
    <!-- delivery_fee Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_fee', trans("lang.market_delivery_fee"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_fee', null,  ['class' => 'form-control','step'=>'any','placeholder'=>  trans("lang.market_delivery_fee_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_delivery_fee_help") }}
            </div>
        </div>
    </div>

    <!-- delivery_range Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_range', trans("lang.market_delivery_range"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_range', null,  ['class' => 'form-control', 'step'=>'any','placeholder'=>  trans("lang.market_delivery_range_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_delivery_range_help") }}
            </div>
        </div>
    </div>

    <!-- default_tax Field -->
    <div class="form-group row ">
        {!! Form::label('default_tax', trans("lang.market_default_tax"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('default_tax', null,  ['class' => 'form-control', 'step'=>'any','placeholder'=>  trans("lang.market_default_tax_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_default_tax_help") }}
            </div>
        </div>
    </div>

    @endhasanyrole

    <!-- Phone Field -->
    <div class="form-group row ">
        {!! Form::label('phone', trans("lang.market_phone"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_phone_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_phone_help") }}
            </div>
        </div>
    </div>

    <!-- Mobile Field -->
    <div class="form-group row ">
        {!! Form::label('mobile', trans("lang.market_mobile"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_mobile_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_mobile_help") }}
            </div>
        </div>
    </div>

    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('address', trans("lang.market_address"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('address', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_address_help") }}
            </div>
        </div>
    </div>

    <!-- Latitude Field -->
    <div class="form-group row ">
        {!! Form::label('latitude', trans("lang.market_latitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_latitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_latitude_help") }}
            </div>
        </div>
    </div>

    <!-- Longitude Field -->
    <div class="form-group row ">
        {!! Form::label('longitude', trans("lang.market_longitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_longitude_help") }}
            </div>
        </div>
    </div>

    <!-- Estimated Time Get Product Field -->
    <div class="form-group row">
        {!! Form::label('estimated_time_get_product', "Tempo estimado para retirada",['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('estimated_time_get_product', ['' => 'Selecione uma estimativa', '10-30 min' => '10-30 min', '30-50 min' => '30-50 min', '50-110 min' => '50-110 min', '130-160 min' => '130-160 min'], $market->estimated_time_get_product??null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">Selecione uma estimativa</div>
        </div>
    </div>

    <!-- Estimated Time Delivery Field -->
    <div class="form-group row">
        {!! Form::label('estimated_time_delivery', "Tempo estimado para delivery",['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('estimated_time_delivery', ['' => 'Selecione uma estimativa', '10-30 min' => '10-30 min', '30-50 min' => '30-50 min', '50-110 min' => '50-110 min', '130-160 min' => '130-160 min'], $market->estimated_time_delivery??null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">Selecione uma estimativa</div>
        </div>
    </div>

    <!-- 'Boolean closed Field' -->
    <div class="form-group row ">
        {!! Form::label('closed', trans("lang.market_closed"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('closed', 0) !!}
                {!! Form::checkbox('closed', 1, null) !!}
            </label>
        </div>
    </div>

    <!-- 'Boolean available_for_delivery Field' -->
    <div class="form-group row ">
        {!! Form::label('available_for_delivery', trans("lang.market_available_for_delivery"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('available_for_delivery', 0) !!}
                {!! Form::checkbox('available_for_delivery', 1, null) !!}
            </label>
        </div>
    </div> 
    <div class="form-group row ">
        <div class="col-3 text-right">
            <div class="checkbox icheck">
                <label class="ml-2 form-check-inline">
                    {!! Form::hidden('exige_agendamento', 0) !!}
                    {!! Form::checkbox('exige_agendamento', 1, null) !!}                   
                </label>
            </div>
        </div>
        <div class="col-9">
            <label for="exige_agendamento" class="control-label text-left">Exigir que o cliente informe uma data e hora nos pedidos deste estabelecimento<br><small></small></label>                    
        </div>
    </div>   
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans("lang.market_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.market_image_help") }}
            </div>
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147011688676454ble = '';
            @if(isset($market) && $market->hasMedia('image'))
                var15671147011688676454ble = {
                name: "{!! $market->getFirstMedia('image')->name !!}",
                size: "{!! $market->getFirstMedia('image')->size !!}",
                type: "{!! $market->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $market->getFirstMedia('image')->collection_name !!}"
            };
                    @endif
            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($market) && $market->hasMedia('image'))
                        dzInit(this, var15671147011688676454ble, '{!! url($market->getFirstMediaUrl('image','thumb')) !!}')
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSending(this, file, formData, '{!! csrf_token() !!}');
                    },
                    maxfilesexceeded: function (file) {
                        dz_var15671147011688676454ble[0].mockFile = '';
                        dzMaxfile(this, file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, var15671147011688676454ble, dz_var15671147011688676454ble[0].mockFile);
                        dz_var15671147011688676454ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, var15671147011688676454ble, '{!! url("markets/remove-media") !!}',
                            'image', '{!! isset($market) ? $market->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147011688676454ble[0].mockFile = var15671147011688676454ble;
            dropzoneFields['image'] = dz_var15671147011688676454ble;


            $(function(){
                //buscar os bairros assim que é carregado
                function buscaBairros(){                    
                    cidade_id = $('select[name="cidade_id"]').val();
                    if(cidade_id != ''){
                        $.ajax({
                            url:"{{route('market.buscaBairros')}}",
                            dataType:"json",
                            method:"POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data:{
                                cidade_id:cidade_id,
                                market_id:{{$market->id??0}}
                            },
                            success:function(data){
                                html = "";                                
                                $.each(data,function(key,value){
                                    html += `
                                    <tr>
                                        <td style="text-align:center"><input type="hidden" name="bairro_id[`+value.id+`]" value="`+value.id+`"><input type="checkbox" name="bairro_ativo[`+value.id+`]" class="checkboxBairro" value="1" data-id="`+value.id+`" `+((value.ativado==1)?'checked="checked"':'')+`></td>
                                        <td>`+value.nome+`</td>
                                        <td><input type="number" placeholder="Digite o valor para o bairro se desejar que ele tenha um valor diferente do padrão" name="bairro_valor[`+value.id+`]" step=".01" value="`+value.valor+`" class="form-control inputBairroValor" data-id="`+value.id+`" `+((value.ativado==1)?'':'disabled="true"')+`> </td>                                        
                                    </tr>
                                    `;
                                });
                                if(html.length == 0){
                                    $('#listaBairros').html("<tr><td colspan='3' style='text-align:center'>Nenhum bairro disponível</td></tr>");
                                }else{
                                    $('#listaBairros').html(html);
                                }
                                
                            },
                            error:function(data){
                                $('#listaBairros').html("<tr><td colspan='3' style='text-align:center'>Ocorreu um erro ao buscar os bairros</td></tr>");
                            }
                        });
                    }
                    
                }

                buscaBairros();

                $('select[name="cidade_id"]').change(function(){
                    buscaBairros();
                });

                $('body').on('change','.checkboxBairro',function(){
                    data_id = $(this).attr('data-id');
                    if($(this).is(':checked')){
                        $('.inputBairroValor[data-id="'+data_id+'"]').removeAttr('disabled');
                    }else{
                        $('.inputBairroValor[data-id="'+data_id+'"]').attr('disabled','disabled');
                    }
                });

            });

        </script>
@endprepend

<!-- Description Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.market_description"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.market_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.market_description_help") }}</div>
        </div>
    </div>
    <!-- Information Field -->
    <div class="form-group row ">
        {!! Form::label('information', trans("lang.market_information"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::textarea('information', null, ['class' => 'form-control','placeholder'=>
             trans("lang.market_information_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.market_information_help") }}</div>
        </div>
    </div>
    
    @if(controlaAceitePagamentosOffline() && auth()->user()->hasRole('admin'))
        <div class="form-group row ">
            <label class="col-3 control-label text-right">Pagamento Offline</label>
            <div class="col-9">
                <div class="row" style="border-bottom:1px solid #333;padding:5px">                                        
                    <div class="checkbox icheck">
                        <label class="ml-2 form-check-inline">
                            {!! Form::hidden('offline_payment_option_cash', 0) !!}
                            {!! Form::checkbox('offline_payment_option_cash', 1, null) !!}
                        </label>
                    </div>
                    {!! Form::label('offline_payment_option_cash', "Dinheiro",['class' => 'control-label text-right']) !!}                            
                </div>                
                <div class="row" style="border-bottom:1px solid #333;padding:5px">                                        
                    <div class="checkbox icheck">
                        <label class="ml-2 form-check-inline">
                            {!! Form::hidden('offline_payment_option_credit', 0) !!}
                            {!! Form::checkbox('offline_payment_option_credit', 1, null) !!}
                        </label>
                    </div>
                    {!! Form::label('offline_payment_option_credit', "Cartão de Crédito",['class' => 'control-label text-right']) !!}                            
                </div>
                <div class="row" style="border-bottom:0px solid #333;padding:5px">                                        
                    <div class="checkbox icheck">
                        <label class="ml-2 form-check-inline">
                            {!! Form::hidden('offline_payment_option_debit', 0) !!}
                            {!! Form::checkbox('offline_payment_option_debit', 1, null) !!}
                        </label>
                    </div>
                    {!! Form::label('offline_payment_option_debit', "Cartão de Débito",['class' => 'control-label text-right']) !!}                            
                </div>                
            </div>
        </div>
    @endif

</div>

<div class="col-12 custom-field-container">
    <div style="flex: 90%;max-width: 50%;padding: 0 4px;" class="column">
        <h5 class="col-12 pb-4">Horário De Funcionamento</h5>
    </div>
    @if(isset($market->openingHourMarket) && count($market->openingHourMarket) < 7)
        <div style="flex: 10%;max-width: 50%;padding: 0 2px;" class="column">
            <div class="alert alert-info" data-toggle="modal" data-target="#ticket_modal">
                <i class="fa fa-plus" style="margin-right: 7px;"></i> Adicionar novo horário de funcionamento
            </div>
        </div>
    @endif
    <div style="flex: 100%;max-width: 100%;padding: 0 4px;" class="column">
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="200">Dia da Semana</th>
                    <th width="100">Horário de Abertura</th>
                    <th width="100">Horário de Fechamento</th>                    
                    <th width="200">Abertura/Fechamento Automatizado</th>                    
                    <th width="80"></th>                    
                </tr>
            </thead>
            <tbody>
                @if(isset($market->openingHourMarket) && count($market->openingHourMarket))
                    @foreach($market->openingHourMarket as $hour)
                        <tr>
                            <td>{{ $hour->day }}</td>
                            <td>{{ $hour->open_hour }}</td>
                            <td>{{ $hour->close_hour }}</td>
                            <td>{{ $hour->automatic_open_close ? 'Sim' : 'Não' }}</td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none" data-toggle="modal" data-target="#ticket_modal" onclick="populateForm({{$hour}})">
                                    <i class="fa fa-edit" style="margin-right: 10px; margin-left: 10px;"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none" onclick="deleteOpeningHour({{ $hour->id }})">
                                    <i class="fa fa-trash" style="margin-left: 10px;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr style="background-color: rgba(0, 0, 0, 0.05)">
                        <td colspan='5' style='text-align:center'>Não existem registros a serem mostrados</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


<div class="col-12 custom-field-container">
    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <h5 class="col-12 pb-4">Valores customizados por bairro</h5>
    </div>
    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <div class="alert alert-warning">
        Bairros não ativados irão utilizar o valor padrão
        </div>
    </div>
    <div style="flex: 100%;max-width: 100%;padding: 0 4px;" class="column">
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="100">Ativo</th>
                    <th width="500">Bairro</th>
                    <th width="500">Valor Customizado<br><small>Valor cobrado no app</small></th>                    
                </tr>
            </thead>
            <tbody id="listaBairros">
                <tr>
                    <td colspan="3" style="text-align:center"> Carregando...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@hasrole('admin')
<div class="col-12 custom-field-container">
    <h5 class="col-12 pb-4">{!! trans('lang.admin_area') !!}</h5>
    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <!-- Users Field -->
        <div class="form-group row ">
            {!! Form::label('users[]', trans("lang.market_users"),['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::select('users[]', $user, $usersSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
                <div class="form-text text-muted">{{ trans("lang.market_users_help") }}</div>
            </div>
        </div>

        @if(setting('enable_pagarme', false))    
            <h2>Split Pagar.me</h2>        
            <div class="alert alert-warning">
                Caso ativado, o valor total dos pedidos, fora a comissão, irá para o recipient do pagar.me
            </div>
            <div class="form-group row ">
                <div class="col-3 text-right">
                    <div class="checkbox icheck">
                        <label class="ml-2 form-check-inline">
                            {!! Form::hidden('pagarme_ativado', 0) !!}
                            {!! Form::checkbox('pagarme_ativado', 1, null) !!}      
                        </label>
                    </div>             
                </div>
                <div class="col-9">                    
                    {!! Form::label('pagarme_ativado', "Ativar Split Pagar.me",['class' => 'control-label text-right']) !!}
                </div>
            </div>
            <div class="form-group row ">
                {!! Form::label('pagarme_recipient_id', "Recipient Id (pagar.me)",['class' => 'col-3 control-label text-right']) !!}
                <div class="col-9">
                    {!! Form::text('pagarme_recipient_id', null,  ['class' => 'form-control', 'placeholder'=>  "Recipient Id do Estabelecimento"]) !!}
                    <div class="form-text text-muted">Id do recipient no pagar.me</div>
                </div>
            </div>
            <div class="form-group row ">
                <div class="col-3 text-right">
                    <div class="checkbox icheck">
                        <label class="ml-2 form-check-inline">
                            {!! Form::hidden('pagarme_recebedor_taxa_entrega', 0) !!}
                            {!! Form::checkbox('pagarme_recebedor_taxa_entrega', 1, null) !!}                   
                        </label>
                    </div>
                </div>
                <div class="col-9">
                    <label for="pagarme_recebedor_taxa_entrega" class="control-label text-left">Enviar Taxa de Entrega para Estabelecimento<br><small>Caso ativado, o valor da taxa de entrega irá para o recipient, senão irá para sua conta</small></label>                    
                </div>
            </div>
        @endif
        
    </div>
    <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <!-- admin_commission Field -->
        <div class="form-group row ">
            {!! Form::label('admin_commission', "Comissão do App %", ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::number('admin_commission', null,  ['class' => 'form-control', 'step'=>'any', 'placeholder'=>  trans("lang.market_admin_commission_placeholder")]) !!}
                <div class="form-text text-muted">
                    {{ trans("lang.market_admin_commission_help") }}
                </div>
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('active', trans("lang.market_active"),['class' => 'col-3 control-label text-right']) !!}
            <div class="checkbox icheck">
                <label class="col-9 ml-2 form-check-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, null) !!}
                </label>
            </div>
        </div>
    </div>
</div>
@endhasrole

@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.market')}}</button>
    <a href="{!! route('markets.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>

<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="modal-header">
                <h5 class="modal-title strong-600 heading-5">{{__('Adicionar um horário de funcionamento')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3 pt-3">
                <form id="one" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" id="divDay">

                        @php
                            $selectDayWeek = \DB::table('opening_hour_markets')->where('market_id', $market->id)->pluck('day');
                            $dayWeek = $selectDayWeek->toArray();
                        @endphp

                        <label for="dayWeek">Dia da semana <span class="text-danger">*</span></label>
                        <select class="form-control" id="day" name="day">
                            <option disabled="" selected="" value="">Selecione</option>
                            @if(!isset($dayWeek) || !in_array("Segunda-feira", $dayWeek))
                                <option value="Segunda-feira">Segunda-feira</option>
                            @endif
                            
                            @if(!isset($dayWeek) || !in_array("Terça-feira", $dayWeek))
                                <option value="Terça-feira">Terça-feira</option>
                            @endif
                            
                            @if(!isset($dayWeek) || !in_array("Quarta-feira", $dayWeek))
                                <option value="Quarta-feira">Quarta-feira</option>
                            @endif
                            
                            @if(!isset($dayWeek) || !in_array("Quinta-feira", $dayWeek))
                                <option value="Quinta-feira">Quinta-feira</option>
                            @endif

                            @if(!isset($dayWeek) || !in_array("Sexta-feira", $dayWeek))
                                <option value="Sexta-feira">Sexta-feira</option>
                            @endif

                            @if(!isset($dayWeek) || !in_array("Sábado", $dayWeek))
                                <option value="Sábado">Sábado</option>
                            @endif

                            @if(!isset($dayWeek) || !in_array("Domingo", $dayWeek))
                                <option value="Domingo">Domingo</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group" style="display: none;" id="divEditDay">
                        <label>Dia da semana <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" id="dayWeek" name="dayWeek" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label>Horário de abertura <span class="text-danger">*</span></label>
                        <input type="time" class="form-control mb-3" name="open_hour" id="open_hour" placeholder="Horário de abertura"  step="1">
                    </div>
                    <div class="form-group">
                        <label>Horário de fechamento <span class="text-danger">*</span></label>
                        <input type="time" class="form-control mb-3" name="close_hour" id="close_hour" placeholder="Horário de fechamento"  step="1">
                    </div>
                    <div class="form-group custom-control custom-checkbox box-client-mobile">
                        <input type="hidden" id="everyday" name="everyday" value="">
                        <input type="checkbox" class="custom-control-input" id="scales" onclick="everydayOption()">
                        <label for="scales" class="custom-control-label" style="padding-top: 2px">Abertura/Fechamento Automático</label>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetForm()">{{__('Cancelar')}}</button>
                        <button type="button" class="btn btn-base-1" onclick="validateForm()">{{__('Adicionar')}}</button>
                    </div>
                    <input type="hidden" id="id_opening_hours" name="id_opening_hours" value="">
                </form>
            </div>
        </div>
    </div>
</div>