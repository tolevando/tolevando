<div class='btn-group btn-group-sm'>
  @can('cidades.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="Editar Cidade" href="{{ route('cidades.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan
  @if(Gate::check('cidades.destroy') || Gate::check('cidades.delete'))  
    {!! Form::open(['route' => ['cidades.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Tem certeza? Esta ação não poderá ser desfeita')"
      ]) !!}
    {!! Form::close() !!}
  @endif
</div>
