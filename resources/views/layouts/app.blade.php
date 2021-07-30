<!DOCTYPE html>
<html lang="{{setting('language','en')}}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="{{$app_logo}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/font-awesome/css/font-awesome.min.css')}}">

    <!-- Ionicons -->
{{--<link href="https://unpkg.com/ionicons@4.1.2/dist/css/ionicons.min.css" rel="stylesheet">--}}
{{--<!-- iCheck -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">--}}
{{--<!-- select2 -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">--}}
<!-- Morris chart -->
{{--<link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">--}}
<!-- jvectormap -->
{{--<link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">--}}
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<!-- Daterange picker -->
{{--<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">--}}
{{--<!-- bootstrap wysihtml5 - text editor -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">--}}

@stack('css_lib')
<!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-sweetalert/sweetalert.css')}}">
    {{--<!-- Bootstrap -->--}}
    {{--<link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}">--}}

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/'.setting("theme_color","primary").'.css')}}">
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XJZZYNYHBK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XJZZYNYHBK');



    window.addEventListener('load', e => {
        
        registerSW(); 
    });
    async function registerSW() { 
        if ('serviceWorker' in navigator) { 
            try {
            await navigator.serviceWorker.register('./sw.js'); 
            } catch (e) {
            //alert('ServiceWorker registration failed. Sorry about that.'); 
            }
        } else {
            document.querySelector('.alert').removeAttribute('hidden'); 
        }
    }



</script>
    @yield('css_custom')
    <link rel="manifest" href="./manifest.json">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="height: 100%; background-color: #f9f9f9;" class="hold-transition sidebar-mini {{setting('theme_color')}}">
@if(auth()->check())
    <div class="wrapper">
        <!-- Main Header -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{setting('fixed_header','')}} {{setting('nav_color','navbar-light bg-white')}} border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('dashboard')}}" class="nav-link">{{trans('lang.dashboard')}}</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @if(env('APP_CONSTRUCTION',false))
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"><i class="fa fa-info-circle"></i>
                            {{env('APP_CONSTRUCTION','') }}</a>
                    </li>
                @endif
                @can('carts.index')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('carts*') ? 'active' : '' }}" href="{!! route('carts.index') !!}"><i class="fa fa-shopping-cart"></i></a>
                    </li>
                @endcan
                @can('notifications.index')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{!! route('notifications.index') !!}"><i class="fa fa-bell"></i></a>
                    </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{auth()->user()->getFirstMediaUrl('avatar','icon')}}" class="brand-image mx-2 img-circle elevation-2" alt="User Image">
                        <i class="fa fa fa-angle-down"></i> {!! auth()->user()->name !!}

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.sidebar')
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer {{setting('fixed_footer','')}}">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
            </div>
            <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="nmain-header navbar navbar-expand {{setting('nav_color','navbar-light bg-white')}} border-bottom">
        <div class="container">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{!! url('/') !!}">{{setting('app_name')}}</a>
                </li>
                @include('layouts.menu',['icons'=>false])
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {!! Auth::user()->name !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> Profile </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
            <!-- Main Footer -->
            <footer class="{{setting('fixed_footer','')}}">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
                </div>
                <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
            </footer>
        </div>
    </div>

    @endrole


    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    {{--<script src="{{asset('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js')}}"></script>--}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    {{--<script>--}}
    {{--$.widget.bridge('uibutton', $.ui.button)--}}
    {{--</script>--}}
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js')}}"></script>

    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js')}}"></script>

    <script type="text/javascript">@include('vendor.notifications.init_firebase')</script>

    <script type="text/javascript">
        const messaging = firebase.messaging();
        navigator.serviceWorker.register("{{url('firebase/sw-js')}}")
            .then((registration) => {
                messaging.useServiceWorker(registration);
                messaging.requestPermission()
                    .then(function() {
                        console.log('Notification permission granted.');
                        getRegToken();

                    })
                    .catch(function(err) {
                        console.log('Unable to get permission to notify.', err);
                    });
                messaging.onMessage(function(payload) {
                    console.log("Message received. ", payload);
                    notificationTitle = payload.data.title;
                    notificationOptions = {
                        body: payload.data.body,
                        icon: payload.data.icon,
                        image:  payload.data.image
                    };
                    var notification = new Notification(notificationTitle,notificationOptions);
                });
            });

        function getRegToken(argument) {
            messaging.getToken().then(function(currentToken) {
                if (currentToken) {
                    saveToken(currentToken);
                    console.log(currentToken);
                } else {
                    console.log('No Instance ID token available. Request permission to generate one.');
                }
            })
                .catch(function(err) {
                    console.log('An error occurred while retrieving token. ', err);
                });
        }


        function saveToken(currentToken) {
            $.ajax({
                type: "POST",
                data: {'device_token': currentToken, 'api_token': '{!! auth()->user()->api_token !!}'},
                url: '{!! url('api/users',['id'=>auth()->id()]) !!}',
                success: function (data) {

                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    </script>

    <!-- Sparkline -->
    {{--<script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script>--}}
    {{--<!-- iCheck -->--}}
    {{--<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>--}}
    {{--<!-- select2 -->--}}
    {{--<script src="{{asset('plugins/select2/select2.min.js')}}"></script>--}}
    <!-- jQuery Knob Chart -->
    {{--<script src="{{asset('plugins/knob/jquery.knob.js')}}"></script>--}}
    <!-- daterangepicker -->
    {{--<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js')}}"></script>--}}
    {{--<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>--}}
    <!-- datepicker -->
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    {{--<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>--}}
    <!-- Slimscroll -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <!-- FastClick -->
    {{--<script src="{{asset('plugins/fastclick/fastclick.js')}}"></script>--}}
    @stack('scripts_lib')
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    {{--<!-- AdminLTE dashboard demo (This is only for demo purposes) -->--}}
    {{--<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>--}}
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>

    <script src="{{asset('js/scripts.js')}}"></script>
    @stack('scripts')
    
    <script type="text/javascript">
    var quantidadePedidosAnterior = null;
    var sonsAtivados = false;
    $(function () {
        var audio = new Audio('{{ asset('notifications/order.mp3') }}');        
        audio.play();
        audio.pause();
        function ajaxBuscaUltimosPedidos(){
            $.ajax({
                url: "{{route('dashboard')}}",
                dataType:'json',
                data:{
                    "action":'ajaxBuscaUltimosPedidos',                                        
                },                    
                method:'GET',
                success: function(data){
                    html = "";
                    primeiro = null;
                    $.each(data,function(i,v){
                        if(primeiro == null){
                            primeiro = v.id;
                        }
                        html += "<tr>";
                            html += "<td style='white-space:nowrap'>"+v.data+"</td>";
                            html += "<td>"+v.cliente+"</td>";
                            html += "<td>"+v.status+"</td>";
                            html += "<td>"+v.metodo_pagamento+"</td>";
                            html += "<td>"+v.produtos+"</td>";
                            html += "<td>"+v.status_pagamento+"</td>";
                            html += "<td>"+v.valor+"</td>";
                            html += "<td><a href='/orders/"+v.id+"' class='btn btn-sm btn-primary' onclick='window.location.reload(true);' target='_blank'>Visualizar</a></td>";
                        html += "</tr>";
                    });
                    $('#listaUltimosPedidos').html(html);                    

                    if(primeiro!=null && quantidadePedidosAnterior != null){
                        if(quantidadePedidosAnterior != primeiro){
                                console.log('TOCANDO NOTIFICAÇÃO');
                                audio.play();

                                setInterval(function() {
                                    audio.play();
                                }, 2500);
                        }
                    }  
                    quantidadePedidosAnterior = primeiro;
                    

                },
                error: function(data){
                    //alert("Ocorreu um erro");
                },
            });
        }

        //audio.play();
        document.addEventListener('DOMContentLoaded', function() {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }

        if (Notification.permission !== 'granted')
            Notification.requestPermission();
        });

        ajaxBuscaUltimosPedidos();
        function notifyMe() {
            // Let's check if the browser supports notifications
            if (!("Notification" in window)) {
                console.log("Seu navegador não suporta notificações");
            }

            // Let's check whether notification permissions have alredy been granted
            else if (Notification.permission === "granted") {
                console.log("aqui");
                // If it's okay let's create a notification
                const notification = new Notification("Você recebeu um novo pedido!");
            }

            // Otherwise, we need to ask the user for permission
            else if (Notification.permission !== 'denied' || Notification.permission === "default") {
                Notification.requestPermission(function (permission) {
                // If the user accepts, let's create a notification
                if (permission === "granted") {
                    const notification = new Notification("Você recebeu um novo pedido!");
                }
                });
            }

            // At last, if the user has denied notifications, and you 
            // want to be respectful there is no need to bother them any more.
        }

        setInterval(ajaxBuscaUltimosPedidos,5000); 
    });
    </script>
</body>
</html>