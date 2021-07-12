<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
  <!-- Name Field -->
  <div class="form-group row ">
    {!! Form::label('cidade', "Cidade", ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::text('cidade', null,  ['class' => 'form-control','placeholder'=>  "Digite o nome da cidade"]) !!}
      <div class="form-text text-muted">
        "Informe o nome da cidade"
      </div>
    </div>
  </div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
  <!-- Description Field -->
  <div class="form-group row ">
    {!! Form::label('uf', "Estado", ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::select('uf', array(
        '' => 'Selecione o estado',
'AC'=>'Acre',
'AL'=>'Alagoas',
'AP'=>'Amapá',
'AM'=>'Amazonas',
'BA'=>'Bahia',
'CE'=>'Ceará',
'DF'=>'Distrito Federal',
'ES'=>'Espírito Santo',
'GO'=>'Goiás',
'MA'=>'Maranhão',
'MT'=>'Mato Grosso',
'MS'=>'Mato Grosso do Sul',
'MG'=>'Minas Gerais',
'PA'=>'Pará',
'PB'=>'Paraíba',
'PR'=>'Paraná',
'PE'=>'Pernambuco',
'PI'=>'Piauí',
'RJ'=>'Rio de Janeiro',
'RN'=>'Rio Grande do Norte',
'RS'=>'Rio Grande do Sul',
'RO'=>'Rondônia',
'RR'=>'Roraima',
'SC'=>'Santa Catarina',
'SP'=>'São Paulo',
'SE'=>'Sergipe',
'TO'=>'Tocantins'
), null,['class' => 'form-control select2']) !!}
      <div class="form-text text-muted"></div>
    </div>
  </div>
</div>
<div style="flex: 100%;max-width: 100%;padding: 0 4px;" class="column">
  <h2>Bairros</h2>
  <div class="form-group row ">
    <div class="col-sm-12">
      <table class="table table-striped table-bordered">
        <thead>
        <tr>
          <th>Nome</th>
          <th>#</th>
        </tr>
        </thead>
        <tbody id="listaBairros">
          @foreach($bairrosPrevius??[] as $bairro)
          <tr>
            <td>{{$bairro->nome}} <input type="hidden" name="bairros[]" value="{{$bairro->nome}}"></td>
            <td><button class="btn btn-danger btn-sm btnRemoveBairro"><i class="fa fa-times"></i></button></td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td><input type="text" name="temp_bairro" class="form-control" placeholder="Digite o nome do bairro e clique em +" value=""></td>
            <td><button type="button" class="btn btn-success btn-sm btnAddCidade"><i class="fa fa-plus"></i></button></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>


@prepend('scripts')
  <script type="text/javascript">
    $(function(){
      var contador = {{count($bairrosPrevius??[])}}
      $('body').on('click','.btnAddCidade', function(){
        nomeBairro = $('input[name="temp_bairro"]').val();
        if(nomeBairro.length == 0){
          alert("Digite o nome do bairro");
        }
        html = ` 
        <tr>
          <td>`+nomeBairro+`<input type="hidden" name="bairros[]" value="`+nomeBairro+`"></td>
          <td><button class="btn btn-danger btn-sm btnRemoveBairro"><i class="fa fa-times"></i></button></td>
        </tr>
        `;
        $('#listaBairros').append(html);
        $('input[name="temp_bairro"]').val("");
      });

      $('body').on('click','.btnRemoveBairro', function(){
        $(this).parent('td').parent('tr').remove();
      });

    });
  </script>
@endprepend
</div>
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} Cidade</button>
  <a href="{!! route('cidades.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancelar</a>
</div>
