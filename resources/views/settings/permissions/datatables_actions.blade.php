<div class='btn-group btn-group-sm'>
    @can('permissions.edit')
        <a href="{{ route('permissions.edit', $id) }}" class='btn btn-link'> <i class="fa fa-edit"></i> </a>
    @endcan
    @can('permissions.destroy')
        {!! Form::open(['route' => ['permissions.destroy', $id], 'method' => 'delete']) !!}
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-link text-danger',
        'onclick' => "return confirm('Tem certeza? Esta ação não poderá ser desfeita')"
    ]) !!}
{!! Form::close() !!}
    @endcan
</div>
