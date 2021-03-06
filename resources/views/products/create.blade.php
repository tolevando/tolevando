@extends('layouts.app')
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
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.product_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.product_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('products.index') !!}">{{trans('lang.product_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.product_create')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('products.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'products.store']) !!}
      <div class="row">
        @include('products.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal')
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

    $( document ).ready(function() {
        console.log( "ready!" );
        var product_name = document.getElementById('product_name').value;

        if (product_name.toLowerCase().includes("pizza meio a meio")) {
          $("#option_mid_pizza_label").show();
          return;
        }

        if (product_name.toLowerCase().includes("pizza 2 sabores")) {
            $("#option_mid_pizza_label").show();
            return;
        }

        if (product_name.toLowerCase().includes("pizza dois sabores")) {
            $("#option_mid_pizza_label").show();
            return;
        }

        if (product_name.toLowerCase().includes("pizza meio ?? meio")) {
            $("#option_mid_pizza_label").show();
            return;
        }

        $("#option_mid_pizza_label").hide();
        $("#option_mid_pizza_value").val(0);

        // switch (product_name.toLowerCase()) {
        //   case 'pizza meio a meio':
        //     $("#option_mid_pizza_label").show();
        //     break;
        //   case 'pizza 2 sabores':
        //     $("#option_mid_pizza_label").show();
        //     break;
        //   case 'pizza dois sabores':
        //     $("#option_mid_pizza_label").show();
        //     break;
        //   case 'pizza meio ?? meio':
        //     $("#option_mid_pizza_label").show();
        //     break;
        //   default:
        //     $("#option_mid_pizza_label").hide();
        //     $("#option_mid_pizza_label").value = 0;
        // }
    });

    $( "#product_name" ).keyup(function() {

      var product_name = document.getElementById('product_name').value;

      if (product_name.toLowerCase().includes("pizza meio a meio")) {
          $("#option_mid_pizza_label").show();
          return;
      }

      if (product_name.toLowerCase().includes("pizza 2 sabores")) {
          $("#option_mid_pizza_label").show();
          return;
      }

      if (product_name.toLowerCase().includes("pizza dois sabores")) {
          $("#option_mid_pizza_label").show();
          return;
      }

      if (product_name.toLowerCase().includes("pizza meio ?? meio")) {
          $("#option_mid_pizza_label").show();
          return;
      }

      $("#option_mid_pizza_label").hide();
      $("#option_mid_pizza_value").val(0);

      // switch (product_name.toLowerCase()) {
      //   case 'pizza meio a meio':
      //     $("#option_mid_pizza_label").show();
      //     break;
      //   case 'pizza 2 sabores':
      //     $("#option_mid_pizza_label").show();
      //     break;
      //   case 'pizza dois sabores':
      //     $("#option_mid_pizza_label").show();
      //     break;
      //   case 'pizza meio ?? meio':
      //     $("#option_mid_pizza_label").show();
      //     break;
      //   default:
      //     $("#option_mid_pizza_label").hide();
      //     $("#option_mid_pizza_value").val(0);
      // }

    });

    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
@endpush