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
        <h1 class="m-0 text-dark">{{trans('lang.market_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.market_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('markets.index') !!}">{{trans('lang.market_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.market_edit')}}</li>
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
        @can('markets.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('markets.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.market_table')}}</a>
        </li>
        @endcan
        @can('markets.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('markets.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.market_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.market_edit')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::model($market, ['route' => ['markets.update', $market->id], 'method' => 'patch']) !!}
      <div class="row">
        @include('markets.fields')
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
  Dropzone.autoDiscover = false;
  var dropzoneFields = [];

  var checked;
  function everydayOption(){
    if($("#scales").prop('checked')){
      checked = 1;

    }else{
      checked = 0;

    }
  }


  function validateForm() { 
    var everyday = document.getElementById("everyday");

    var day = document.getElementById("day");
    var open_hour = document.getElementById("open_hour");
    var close_hour = document.getElementById("close_hour");
    var id_opening_hours = document.getElementById("id_opening_hours");
    var dayWeek = document.getElementById("dayWeek");

    if (id_opening_hours.value == "") {
      if (day.value == ""){
        Swal.fire({
          // title: 'Error!',
          text: 'Necessário preencher um dia da semana!',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        event.preventDefault();
        return false;
      }
    }




    if (checked == '1') {
      everyday.value = 1;
    }else{
      everyday.value = 0;
    }

    if (open_hour.value == ""){
      Swal.fire({
        // title: 'Error!',
        text: 'Necessário preencher a hora de abertura!',
        icon: 'error',
        confirmButtonText: 'OK'
      })
      event.preventDefault();
      return false;
    }

    if (close_hour.value == ""){
      Swal.fire({
        // title: 'Error!',
        text: 'Necessário preencher a hora de fechamento!',
        icon: 'error',
        confirmButtonText: 'OK'
      })
      event.preventDefault();
      return false;
    }

    if (close_hour.value <= open_hour.value){
      Swal.fire({
        // title: 'Error!',
        text: 'A hora de fechamento deve ser maior que a hora de abertura!',
        icon: 'error',
        confirmButtonText: 'OK'
      })
          event.preventDefault();
          return false;
    }

    if($("#scales").prop('checked')){
      everyday.value = 1;

    }else{
      everyday.value = 0;

    }

    $.ajax({
          type: "POST",
          dataType:"json",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {'day': (dayWeek.value ? dayWeek.value : day.value), 'open_hour': open_hour.value, 'close_hour': close_hour.value, 'automatic_open_close': everyday.value},
          url: '{!! url('markets/opening_hours', ['id' => $market->id ]) !!}',
          success: function (data) {
            console.log("SUCCESS DATA:", data);

            if (data.statusCode == '200') {
              $('#ticket_modal').modal('hide');
              resetForm();
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.msg,
                showConfirmButton: false,
                timer: 2000
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
  }

  function resetForm() {
    document.getElementById("divDay").style.display="block";
    document.getElementById("divEditDay").style.display="none";
    document.getElementById("id_opening_hours").value = '';

    document.getElementById("day").value = '';
    document.getElementById("open_hour").value = '';
    document.getElementById("close_hour").value = '';
    document.getElementById("scales").checked = false;

    return $('#myForm').trigger("reset");
  }

  function populateForm(data) {
    document.getElementById("open_hour").value = data.open_hour;
    document.getElementById("close_hour").value = data.close_hour;
    document.getElementById("id_opening_hours").value = data.id;
    document.getElementById("divDay").style.display="none";
    document.getElementById("divEditDay").style.display="block";
    document.getElementById("dayWeek").value = data.day;

    if (data.automatic_open_close) {
      document.getElementById("scales").checked = true;
    }
  }

  function deleteOpeningHour(id) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
      title: 'Deseja excluir o registro?',
      // text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
          type: "POST",
          dataType:"json",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{!! url('markets/opening_hours/delete') !!}',
          data:{
              id: id,
          },
          success: function (data) {
            console.log("SUCCESS DATA:", data);

            if (data.statusCode == '200') {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Registro Deletado!',
                showConfirmButton: false,
                timer: 2000
              });

              window.location.reload(true);
              return true;
            }

            swalWithBootstrapButtons.fire(
                'Erro ao deletar registro!',
                '',
                'error'
              )
          },
          error: function (err) {
              console.log('ERROR: ', err);
          }
        });
      }
    })
  }

</script>
@endpush