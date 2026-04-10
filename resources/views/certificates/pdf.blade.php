@php
$layout = $cert->template->layout;
$data = $cert->data;
@endphp

<div style="position:relative; width:100%; height:100%;">

<div style="position:absolute; top:{{$layout['student_name']['y']}}px; left:{{$layout['student_name']['x']}}px;">
    {{ $data['student_name'] }}
</div>

<div style="position:absolute; top:{{$layout['belt_level']['y']}}px; left:{{$layout['belt_level']['x']}}px;">
    {{ $data['belt_level'] }}
</div>

</div>
