@php
$layout = $cert->template->layout;
$data = $cert->data;
@endphp

<div style="position:relative; width:1123px; height:794px;">

@if($cert->template->background)
<img src="{{ asset('storage/'.$cert->template->background) }}"
     style="position:absolute; width:100%; height:100%;">
@endif

@foreach($layout['objects'] ?? [] as $obj)

@if($obj['type'] == 'text')
<div style="
    position:absolute;
    top:{{$obj['y']}}px;
    left:{{$obj['x']}}px;
    font-size:{{$obj['fontSize']}}px;
    font-family:{{$obj['fontFamily']}};
    color:{{$obj['fill']}};
    font-weight:{{$obj['fontWeight'] ?? 'normal'}};
">
    {{ $data[$obj['key']] ?? $obj['key'] }}
</div>
@endif

@if($obj['type'] == 'image')
<img src="{{ asset('storage/'.$obj['src']) }}"
     style="position:absolute;
     top:{{$obj['y']}}px;
     left:{{$obj['x']}}px;
     transform: scale({{$obj['scaleX']}},{{$obj['scaleY']}});">
@endif

@endforeach

</div>
