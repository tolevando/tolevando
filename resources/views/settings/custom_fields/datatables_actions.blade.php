<div class='btn-group btn-group-sm'>
    @can('customFields.edit') 
        @if(!in_array($id,[4,5]))
            <a href="{{ route('customFields.edit', $id) }}" class='btn btn-link'> <i class="fa fa-edit"></i> </a>
        @endif
    @endcan
    @can('customFields.destroy')
        @if(!in_array($id,[4,5]))
            {!! Form::open(['route' => ['customFields.destroy', $id], 'method' => 'delete']) !!}
            {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-link text-danger',
            'onclick' => "return confirm('Tem certeza? Esta ação não poderá ser desfeita')"
            ]) !!}  
            {!! Form::close() !!}
        @endif
    @endcan
</div>
