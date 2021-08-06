@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="padding: 0 4px;" class=" row">
<!-- Name Field -->
  <div class="form-group row col-5">
    {!! Form::label('name', trans("lang.option_group_name"), ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.option_group_name_placeholder")]) !!}
      <div class="form-text text-muted">
        {{ trans("lang.option_group_name_help") }}
      </div>
    </div>
  </div>

  <div class="form-group row col-4">
    {!! Form::label('is_required', 'Opção Obrigatória',['class' => 'col-9 control-label text-right']) !!}
      <div class="checkbox icheck">
          <label class="col-9 ml-2 form-check-inline">
              {!! Form::hidden('is_required', 0) !!}
              {!! Form::checkbox('is_required', 1, null) !!}
          </label>
      </div>
  </div>

  {!! Form::hidden('market_id', (count(Auth::user()->markets) ? Auth::user()->markets->first()->id : null)) !!}

  <div class="form-group row col-3">
    {!! Form::label('is_unique', 'Opção Única',['class' => 'col-8 control-label text-right']) !!}
      <div class="checkbox icheck">
          <label class="col-9 ml-2 form-check-inline">
              {!! Form::hidden('is_unique', 0) !!}
              {!! Form::checkbox('is_unique', 1, null) !!}
          </label>
      </div>
      <div class="form-text text-muted">
        Limita a escolha de apenas uma opção no produto.
      </div>
  </div>
</div>


@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.option_group')}}</button>
  <a href="{!! route('optionGroups.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
