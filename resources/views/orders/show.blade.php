@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.order_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.order_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('orders.index') !!}">{{trans('lang.order_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.order')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="card">
    <div class="card-header d-print-none">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('orders.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.order_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.order')}}</a>
        </li>
        <div class="ml-auto d-inline-flex">
          @if(isset($order->order_status_id) && ($order->order_status_id == 1 && $order->active != 0))
            <li class="nav-item" style="white-space:nowrap">
              <a class="nav-link pt-1"style="color: #28a745!important;" href="javascript:void(0);" id="updateOrderStatus"><i class="fa fa-check" style="color: #28a745!important;"></i> <b>Aceitar</b></a>
            </li>
            <li class="nav-item" style="white-space:nowrap">
              <a class="nav-link pt-1" href="javascript:void(0);" style="color: red!important;" data-toggle="modal" data-target="#reason_cancel_modal"><i class="fa fa-close" style="color: red!important;"></i> <b>Recusar</b></a>
            </li>
          @endif

          @if(isset($order->order_status_id) && ($order->order_status_id != 1 && $order->order_status_id != 4 && $order->active != 0))
            <li class="nav-item" style="white-space:nowrap">
              <a class="nav-link pt-1"style="color: #28a745!important;" href="javascript:void(0);" id="goToDelivery"><i class="fa fa-motorcycle" style="color: #28a745!important;"></i> <b>Saiu para entrega</b></a>
            </li>
          @endif
          <li class="nav-item" style="white-space:nowrap">
            <a class="nav-link pt-1" href="{{route('orders.edit',[$order->id])}}"><i class="fa fa-edit"></i> Editar</a>
          </li>
          <li class="nav-item" style="white-space:nowrap">
            
            <a class="nav-link pt-1" id="printOrder" href="#"><i class="fa fa-print"></i> {{trans('lang.print')}}</a>            
          </li>
          </li>
          <li class="nav-item" style="white-space:nowrap">
            
            <a class="nav-link pt-1" id="printOrder58mm" href="#"><i class="fa fa-print"></i> {{trans('lang.print')." 58mm"}}</a>
            
          </li>
          <li class="nav-item" style="white-space:nowrap">
            
            <a class="nav-link pt-1" id="printOrder80mm" href="#"><i class="fa fa-print"></i> {{trans('lang.print')." 80mm"}}</a>
            
          </li>
        </div>
      </ul>
    </div>
    <div class="card-body">
      <div class="row">
        @include('orders.show_fields')
      </div>
      @include('product_orders.table')
      <div class="row">
      <div class="col-5 offset-7">
        <div class="table-responsive table-light">
          <table class="table">
            <tbody><tr>
              <th class="text-right">{{trans('lang.order_subtotal')}}</th>
              <td>{!! getPrice($subtotal) !!}</td>
            </tr>
            <tr>
              <th class="text-right">{{trans('lang.order_delivery_fee')}}</th>
              <td>{!! getPrice($order['delivery_fee'])!!}</td>
            </tr>
            <tr>
              <th class="text-right">{{trans('lang.order_tax')}} ({!!$order->tax!!}%) </th>
              <td>{!! getPrice($taxAmount)!!}</td>
            </tr>

            <tr>
              <th class="text-right">Desconto ({{$order->coupon->code??"-"}})</th>
              <td>{!!getPrice($desconto??0.00)!!}</td>
            </tr>
            <tr>
              <th class="text-right">{{trans('lang.order_total')}}</th>
              <td>{!!getPrice($total)!!}</td>
            </tr>
            </tbody></table>
        </div>
      </div>
      </div>
      <div class="clearfix"></div>
      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('orders.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<div id="printDiv58mm" style="width:218px;visibility:hidden">
<table width="218" style="border:0px">
  <tr>
    <td style="text-align:center" colspan="2"><img src="{{$app_logo}}" style="max-width:50px"><br>{{setting('app_name')}}</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center"><b>{{$order->productOrders[0]->product->market->name}}</b></td>  
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Pedido</b></td>
    <td>#{!! $order->id !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Data</b></td>
    <td>{!! $order->created_at->format('d/m/Y H:i') !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Cliente</b></td>
    <td>{!! $order->user->name !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Pagamento por</b></td>
    <td>{!! isset($order->payment) ? $order->payment->method : ''  !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000"> 
    <td><b>Endereço</b></td>
    <td>{!! $order->deliveryAddress ? $order->deliveryAddress->address : 'Não há. Cliente optou por retirar' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Telefone</b></td>
    <td>{!! isset($order->user->custom_fields['phone']) ? $order->user->custom_fields['phone']['view'] : "" !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Observação</b></td>
    <td>{!! $order->observacao??'-' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td style="text-align:center" colspan="2"><b>Pedido</b></th>    
  </tr>
  @foreach($order->productOrders as $productOrder)
  <tr style="border-bottom:1px solid #000">
  <td><b>{{$productOrder->product->name}} - 
  @foreach ($productOrder->options as $option)
    {!!$option->name;!!}        
  @endforeach
  </b></td>
  <td>{{number_format($productOrder->price,2,',','.')}}</td>
  </td>
  @endForeach
  <tr style="border-bottom:1px solid #000">
    <td><b>Observação</b></td>
    <td>{!! $order->observacao??'-' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Troco Para</b></td>
    <td>{!! $order->troco_para??'-' !!}</td>
  </tr>
  <tr>
    <td><b>{{trans('lang.order_subtotal')}}</b></th>
    <td>{!! getPrice($subtotal) !!}</td>
  </tr>
  <tr>
    <td><b>{{trans('lang.order_delivery_fee')}}</b></th>
    <td>{!! getPrice($order['delivery_fee'])!!}</td>
  </tr>
  <tr >
    <td><b>{{trans('lang.order_tax')}} ({!!$order->tax!!}%) </b></th>
    <td>{!! getPrice($taxAmount)!!}</td>
  </tr>   
  <tr>
    <td><b>Desconto ({{$order->coupon->code??"-"}})</b></th>
    <td>{!!getPrice($desconto??0.00)!!}</td>
  </tr> 
  <tr style="border-bottom:1px solid #000">
    <td><b>{{trans('lang.order_total')}}</b></th>
    <td>{!!getPrice($total)!!}</td>
  </tr>     
  <tr>
    <td colspan="2" style="text-align:center"><b>{{$order->productOrders[0]->product->market->name}}</b></td>  
  </tr>
  <tr>
    <td style="text-align:center" colspan="2">{{setting('app_name')}}<br><img src="{{$app_logo}}" style="max-width:50px"></td>
  </tr>           
</table>
</div>
<div id="printDiv80mm" style="width:302px;visibility:hidden">
<table width="302" style="border:0px">
  <tr>
    <td style="text-align:center" colspan="2"><img src="{{$app_logo}}" style="max-width:50px"><br>{{setting('app_name')}}</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center"><b>{{$order->productOrders[0]->product->market->name}}</b></td>  
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Pedido</b></td>
    <td>#{!! $order->id !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Data</b></td>
    <td>{!! $order->created_at->format('d/m/Y H:i') !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Cliente</b></td>
    <td>{!! $order->user->name !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Pagamento por</b></td>
    <td>{!! isset($order->payment) ? $order->payment->method : ''  !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000"> 
    <td><b>Endereço</b></td>
    <td>{!! $order->deliveryAddress ? $order->deliveryAddress->address : 'Não há. Cliente optou por retirar' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Telefone</b></td>
    <td>{!! isset($order->user->custom_fields['phone']) ? $order->user->custom_fields['phone']['view'] : "" !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Observação</b></td>
    <td>{!! $order->observacao??'-' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td><b>Troco Para</b></td>
    <td>{!! $order->troco_para??'-' !!}</td>
  </tr>
  <tr style="border-bottom:1px solid #000">
    <td style="text-align:center" colspan="2"><b>Pedido</b></th>    
  </tr>
  @foreach($order->productOrders as $productOrder)
  <tr style="border-bottom:1px solid #000">
  <td><b>{{$productOrder->product->name}} - 
  @foreach ($productOrder->options as $option)
    {!!$option->name;!!}        
  @endforeach
  </b></td>
  <td>{{number_format($productOrder->price,2,',','.')}}</td>
  </td>
  @endForeach
  <tr style="border-bottom:1px solid #000">
    <td><b>Observação</b></td>
    <td>{!! $order->observacao??'-' !!}</td>
  </tr>
  <tr>
    <td><b>{{trans('lang.order_subtotal')}}</b></th>
    <td>{!! getPrice($subtotal) !!}</td>
  </tr>
  <tr>
    <td><b>{{trans('lang.order_delivery_fee')}}</b></th>
    <td>{!! getPrice($order['delivery_fee'])!!}</td>
  </tr>
  <tr >
    <td><b>{{trans('lang.order_tax')}} ({!!$order->tax!!}%) </b></th>
    <td>{!! getPrice($taxAmount)!!}</td>
  </tr>    
  <tr>
    <td><b>Desconto ({{$order->coupon->code??"-"}})</b></th>
    <td>{!!getPrice($desconto??0.00)!!}</td>
  </tr> 
  <tr style="border-bottom:1px solid #000">
    <td><b>{{trans('lang.order_total')}}</b></th>
    <td>{!!getPrice($total)!!}</td>
  </tr>     
  <tr>
    <td colspan="2" style="text-align:center"><b>{{$order->productOrders[0]->product->market->name}}</b></td>  
  </tr>
  <tr>
    <td style="text-align:center" colspan="2">{{setting('app_name')}}<br><img src="{{$app_logo}}" style="max-width:50px"></td>
  </tr>           
</table>
</div>

<div class="modal fade" id="reason_cancel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="modal-header">
                <h5 class="modal-title strong-600 heading-5">{{__('Motivo do cancelamento:')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3 pt-3">
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <span class="text-danger">* Necessário preencher esse campo</span>
                        <textarea name="reason_cancel" id="reason_cancel"  class="form-control" rows="3" style="resize: none;" maxlength="50"></textarea>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="resetReasonCancel">{{__('Voltar')}}</button>
                        <button type="button" class="btn btn-base-1" id="sendReasonCancel">{{__('Enviar')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
  <script type="text/javascript">

    $("#sendReasonCancel").on("click",function () {

      var reason_cancel = document.getElementById("reason_cancel");

      if (reason_cancel.value == '') {
        Swal.fire({
          // title: 'Error!',
          text: 'Necessário preencher o campo!',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        event.preventDefault();
        return false;
      }

      $.ajax({
          type: "POST",
          dataType:"json",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: { 'reason_cancel': reason_cancel.value },
          url: '{!! url('order/update_reason_cancel_order', ['id' => $order->id ]) !!}',
          success: function (data) {
            console.log("SUCCESS DATA:", data);

            if (data.statusCode == '200') {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.msg,
                showConfirmButton: false,
                timer: 1000
              });

              window.location.reload(true);
              return true;
            }

            Swal.fire({
              position: 'top-end',
              icon: 'error',
              title: 'Erro ao atualizar registro',
              showConfirmButton: false,
              timer: 2000
            });
          },
          error: function (err) {
              console.log('ERROR: ', err);
          }
      });
    });

    $("#resetReasonCancel").on("click",function () {
      var reason_cancel = document.getElementById("reason_cancel");
      reason_cancel.value = '';
    });
    

    $("#updateOrderStatus").on("click",function () {
      $.ajax({
          type: "POST",
          dataType:"json",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {},
          url: '{!! url('order/update_order_status', ['id' => $order->id ]) !!}',
          success: function (data) {
            console.log("SUCCESS DATA:", data);

            if (data.statusCode == '200') {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.msg,
                showConfirmButton: false,
                timer: 1000
              });

              window.location.reload(true);
              return true;
            }

            Swal.fire({
              position: 'top-end',
              icon: 'error',
              title: 'Erro ao atualizar registro',
              showConfirmButton: false,
              timer: 2000
            });
          },
          error: function (err) {
              console.log('ERROR: ', err);
          }
      });
    });

    $("#goToDelivery").on("click",function () {
      $.ajax({
          type: "POST",
          dataType:"json",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: { "action" : "go_to_delivery"},
          url: '{!! url('order/update_order_status', ['id' => $order->id ]) !!}',
          success: function (data) {
            console.log("SUCCESS DATA:", data);

            if (data.statusCode == '200') {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.msg,
                showConfirmButton: false,
                timer: 1000
              });

              window.location.reload(true);
              return true;
            }

            Swal.fire({
              position: 'top-end',
              icon: 'error',
              title: 'Erro ao atualizar registro',
              showConfirmButton: false,
              timer: 2000
            });
          },
          error: function (err) {
              console.log('ERROR: ', err);
          }
      });
    });

    $("#printOrder").on("click",function () {
      window.print();
    });

    $(document).ready(function () {
        function PrintElem(elem) {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('</head><body>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            mywindow.document.write('</body></html>');

            //mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            setTimeout(function () {
                mywindow.print();
                //mywindow.close();
            }, 500);
            //mywindow.close();

            return true;
        }

        $('#printOrder80mm').click(function () {
            PrintElem("printDiv80mm");
        });
        $('#printOrder58mm').click(function () {
            PrintElem("printDiv58mm");
        });
    });
</script>

@endpush
