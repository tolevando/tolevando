@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    {{--dropzone--}}
    <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('settings_title',trans('lang.user_table'))
@section('settings_content')
    @include('flash::message')
    @include('adminlte-templates::common.errors')
    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">

                <li class="nav-item">
                    <a class="nav-link" href="{!! url('settings/payment/payment') !!}"><i class="fa fa-money mr-2"></i>{{trans('lang.app_setting_payment')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{!! url('settings/payment/paypal') !!}"><i class="fa fa-envelope mr-2"></i>{{trans('lang.app_setting_paypal')}}@if(setting('enable_paypal', false))<span class="badge ml-2 badge-success">{{trans('lang.active')}}</span>@endif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{!! url('settings/payment/stripe') !!}"><i class="fa fa-envelope-o mr-2"></i>{{trans('lang.app_setting_stripe')}}@if(setting('enable_stripe',false))<span class="badge ml-2 badge-success">{{trans('lang.active')}}</span>@endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{!! url('settings/payment/pagarme') !!}"><i class="fa fa-envelope-o mr-2"></i>{{"Pagar.me"}}@if(setting('enable_pagarme',false))<span class="badge ml-2 badge-success">{{trans('lang.active')}}</span>@endif
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row">
                <div style="flex: 70%;max-width: 70%;padding: 0 4px;" class="column">
                <!-- 'Boolean enable_facebook Field' -->
                    <div class="form-group row col-12">
                        {!! Form::label('enable_pagarme', "Habilitar Pagar.me ",['class' => 'col-3 control-label text-right']) !!}
                        <div class="checkbox icheck">
                            <label class="w-100 ml-2 form-check-inline">
                                {!! Form::hidden('enable_pagarme', null) !!}
                                {!! Form::checkbox('enable_pagarme', 1, setting('enable_pagarme', false)) !!}
                                <span class="ml-2">{!! "Marque para habilitar o Pagar.me" !!}</span>
                            </label>
                        </div>
                    </div>
                    <!-- facebook_app_id Field -->
                    <div class="form-group row col-12">
                        {!! Form::label('pagarme_key', "Pagar.me Key", ['class' => 'col-3 control-label text-right']) !!}
                        <div class="col-9">
                            {!! Form::text('pagarme_key', setting('pagarme_key'),  ['class' => 'form-control','placeholder'=>  "Sua Key do Pagar.me"]) !!}
                            <div class="form-text text-muted">
                                Inserir Pagar.me Key
                            </div>
                        </div>
                    </div>

                    <!-- facebook_app_secret Field -->
                    <div class="form-group row col-12">
                        {!! Form::label('pagarme_secret', "Pagar.me Secret", ['class' => 'col-3 control-label text-right']) !!}
                        <div class="col-9">
                            {!! Form::text('pagarme_secret', setting('pagarme_secret'),  ['class' => 'form-control','placeholder'=>  "Seu Secret do Pagar.me"]) !!}
                            <div class="form-text text-muted">
                            Inserir Pagar.me Secret
                            </div>
                        </div>
                    </div>
                    <!-- facebook_app_secret Field -->
                    <div class="form-group row col-12">
                        {!! Form::label('pagarme_recipient_id', "Pagar.me Recipient ID", ['class' => 'col-3 control-label text-right']) !!}
                        <div class="col-9">
                            {!! Form::text('pagarme_recipient_id', setting('pagarme_recipient_id'),  ['class' => 'form-control','placeholder'=>  "Seu Recipient do Pagar.me"]) !!}
                            <div class="form-text text-muted">
                            Inserir o seu recipient id do pagar.me
                            </div>
                        </div>
                    </div>
                </div>

                <div style="flex: 30%;max-width: 30%;padding: 0 4px;" class="column">
                    <!-- TODO explain stripe here-->
                </div>
                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_payment')}}</button>
                    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
                </div>

            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
    @include('layouts.media_modal',['collection'=>null])
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    {{--dropzone--}}
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>
@endpush
