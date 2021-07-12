@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
 # {{ $greeting }}
 @else
@if ($level == 'error')
# @lang('Whoops!')
@else
# @lang('Olá!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
case 'success':
$color = 'green';
break;
case 'error':
$color = 'red';
break;
default:
$color = 'blue';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Atenciosamente'),<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
"Se você está tendo problemas ao clicar no botão \":actionText\", copie e cole a URL abaixo\nno seu navegador: [:actionURL](:actionURL)\n",
[
'actionText' => $actionText,
'actionURL' => $actionUrl
]
)
@endcomponent
@endisset
@endcomponent
