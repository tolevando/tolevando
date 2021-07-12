@extends('layouts.app')
@section('css_custom')
<link rel="stylesheet" href="/dist/jquery.switcher/dist/switcher.min.css">
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header content-header{{setting('fixed_header')}}">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('lang.dashboard')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.dashboard')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="content">        
        <div class="row mb-2">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body" style="text-align:center">
                        <h5 style="vertical-align: top;">Estabelecimento Aberto?</h5>
                        <input type="checkbox" name="aberto" value="S" {{(($market->closed==0)?'checked="checked"':'')}} />
                    </div>
                </div>                
            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body" style="text-align:center">
                        <h5 style="vertical-align: top;">Delivery Ativado?</h5>
                        <input type="checkbox" name="delivery" value="S" {{(($market->available_for_delivery==1)?'checked="checked"':'')}} />                        
                    </div>
                </div>
            </div>
            <hr><br>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-4 col-4">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="totalPedidosHoje"><div class="spinner-border" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div></h3>

                        <p>Nº de Pedidos Hoje</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-bag"></i>
                    </div>
                    
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-4">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        
                        <h3 id="ganhosHoje"><div class="spinner-border" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div></h3>                        
                        <p>Ganhos Hoje (R$)</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->            
            <div class="col-lg-4 col-4">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="totalClientes"><div class="spinner-border" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div></h3>
                        <p>Nº de Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-group"></i>
                    </div>
                    
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row mb-2">
            <div class="col-md-12 col-12">
                <div class="card">                    
                    <div class="card-body" style="">
                    <h5 class="card-title">Últimos Pedidos <a href="{{route('orders.index')}}" class="btn btn-sm btn-primary float-right">Acessar Todos os Pedidos</a></h5>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Status</th>                                    
                                    <th>Método Pgto.</th>
                                    <th>Produto(s)</th>
                                    <th>Status Pgto.</th>
                                    <th>Valor</th>
                                    <th>Ações</th>                                    
                                </tr>
                            </thead>
                            <tbody id="listaUltimosPedidos">

                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    
@endsection
@push('scripts_lib')
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="/dist/jquery.switcher/dist/switcher.js"></script>
@endpush
@push('scripts')
    <script type="text/javascript">
    var quantidadePedidosAnterior = null;
    var sonsAtivados = false;
    $(function () {
        //var audio = new Audio('{{url("notification.mp3")}}');        
        $('input[name="aberto"]').switcher({
            style:'default',            
            language:'pt'
        });
        $('input[name="delivery"]').switcher({
            style:'default',            
            language:'pt'
        });
                        
        $('#btnQueroAtivarOSom').click(function(){
            sonsAtivados = true;
            //audio.play();
            $('#divAtivarSons').html("<i class='fa fa-bell' style='font-size:48px'></i><br><h4>Aumente o Volume ;)<br>Sons Ativados</h4><h6>O som foi tocado para fins de teste</h6>");            
            $('#divAtivarSons').fadeOut(5000);
        });

        $('input[name="aberto"],input[name="delivery"]').change(function(){            
            var estabelecimentoAberto = $('input[name="aberto"]:checked').length;
            var deliveryAtivo = $('input[name="delivery"]:checked').length;
            delivery = 'N';
            if(deliveryAtivo != 0){                
                delivery = 'S';
            }            

            var aberto = 'N';            
            if(estabelecimentoAberto != 0){                
                aberto = 'S';
            }

            $.ajax({
                url: "{{route('dashboard')}}",
                dataType:'json',
                data:{
                    "action":'ajaxAlteraEstadoEstabelecimento',
                    'aberto':aberto,
                    'delivery':delivery
                },                    
                method:'GET',
                success: function(data){
                    if(data.success){

                    }else{
                        
                    }
                },
                error: function(data){
                    alert("Ocorreu um erro");
                },
            });
            
            

        });


        
        //notifyMe();

        function buscaNumeros(){
            $.ajax({
                url: "{{route('dashboard')}}",
                dataType:'json',
                data:{
                    "action":'ajaxRetornaNumerosDashboardEstabelecimento',   
                                    
                },                    
                method:'GET',
                success: function(data){
                    if(data.success){                        
                        $('#totalPedidosHoje').html(data.pedidosHoje);
                        $('#ganhosHoje').html(data.ganhosHoje);
                        $('#totalClientes').html(data.numeroClientes);                        
                    }else{
                        
                    }
                },
                error: function(data){
                    alert("Ocorreu um erro");
                },
            });
            
        }    

        buscaNumeros();   

        setInterval(buscaNumeros,5000); 
        

    });
    </script>
@endpush