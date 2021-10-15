<!-- Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('id', trans('lang.order_id'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>#{!! $order->id !!}</p>
  </div>

    {!! Form::label('order_client', trans('lang.order_client'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! $order->user->name !!}</p>
  </div>

    {!! Form::label('order_client_phone', trans('lang.order_client_phone'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! isset($order->user->custom_fields['phone']) ? $order->user->custom_fields['phone']['view'] : "" !!}</p>
  </div>

    {!! Form::label('delivery_address', trans('lang.delivery_address'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! $order->deliveryAddress ? $order->deliveryAddress->address : 'Não há. Cliente optou por retirar' !!}</p>
  </div>

    {!! Form::label('number', 'Número residencial', ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! $order->deliveryAddress ? ($order->deliveryAddress->number ? : '-') : 'Não há. Cliente optou por retirar' !!}</p>
  </div>

    {!! Form::label('order_date', trans('lang.order_date'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! $order->created_at->format('d/m/Y H:i:s') !!}</p>
  </div>
  
  @if($order->productOrders[0]->product->market->exige_agendamento)
    {!! Form::label('data_hora', "Data Agendada", ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
      <p>{!! $order->data_hora !!}</p>
    </div>
  @endif  

  @if($order->active == 0 && $order->reason_cancel)
    {!! Form::label('reason_cancel', "Motivo Cancelamento", ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
      <p>{!! $order->reason_cancel !!}</p>
    </div>
  @endif


</div>

<!-- Order Status Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('order_status_id', trans('lang.order_status_status'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! $order->orderStatus->status  !!}</p>
  </div>

    {!! Form::label('active', trans('lang.order_active'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    @if($order->active)
      <p><span class='badge badge-success'> {{trans('lang.yes')}}</span></p>
      @else
      <p><span class='badge badge-danger'>{{trans('lang.order_canceled')}}</span></p>
      @endif
  </div>

    {!! Form::label('payment_method', trans('lang.payment_method'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! isset($order->payment) ? $order->payment->method : ''  !!}</p>
  </div>

    {!! Form::label('payment_status', trans('lang.payment_status'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
    <p>{!! isset($order->payment) ? $order->payment->status : trans('lang.order_not_paid')  !!}</p>
  </div>
    {!! Form::label('order_updated_date', trans('lang.order_updated_at'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->updated_at->format('d/m/Y H:i:s') !!}</p>
    </div>

</div>

<!-- Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('market', trans('lang.market'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if(isset($order->productOrders[0]))
            <p>{!! $order->productOrders[0]->product->market->name !!}</p>
        @endif
    </div>

    {!! Form::label('market_address', trans('lang.market_address'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if(isset($order->productOrders[0]))
            <p>{!! $order->productOrders[0]->product->market->address !!}</p>
        @endif
    </div>

    {!! Form::label('market_phone', trans('lang.market_phone')." do Estabelecimento", ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if(isset($order->productOrders[0]))
            <p>{!! $order->productOrders[0]->product->market->phone !!}</p>
        @endif
    </div>

    {!! Form::label('driver', trans('lang.driver')." do Estabelecimento", ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if(isset($order->driver))
            <p>{!! $order->driver->name !!}</p>
        @else
            <p>{{trans('lang.order_driver_not_assigned')}}</p>
        @endif

    </div>

    {!! Form::label('hint', 'Dica:', ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->hint !!}</p>
    </div>
    
    {!! Form::label('observacao', 'Observacao do Cliente', ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->observacao !!}</p>
    </div>
    
    @if($order->payment->method == 'Cartão de Crédito na Entrega' || $order->payment->method == 'Cartão de Débito na Entrega')
    
        {!! Form::label('card_brand', 'Bandeira do Cartão', ['class' => 'col-4 control-label']) !!}
        <div class="col-8">
            <p>{!! $order->card_brand??'-' !!}</p>
        </div>

    @endif

    {!! Form::label('troco_para', 'Troco para', ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->troco_para??'-' !!}</p>
    </div>

</div>

{{--<!-- Tax Field -->--}}
{{--<div class="form-group row col-md-6 col-sm-12">--}}
{{--  {!! Form::label('tax', 'Tax:', ['class' => 'col-4 control-label']) !!}--}}
{{--  <div class="col-8">--}}
{{--    <p>{!! $order->tax !!}</p>--}}
{{--  </div>--}}
{{--</div>--}}


