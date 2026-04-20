<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin:0; }

        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .bg {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .text {
            position: absolute;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">

    {{-- BACKGROUND --}}
    @if($template->background)
        <img src="{{ public_path('storage/'.$template->background) }}" class="bg">
    @endif

    {{-- STUDENT NAME --}}
    <div class="text" style="top: {{ $layout['student_name']['y'] }}px; left: {{ $layout['student_name']['x'] }}px;">
        {{ $cert->student->first_name }} {{ $cert->student->last_name }}
    </div>

    {{-- DATE --}}
    <div class="text" style="top: {{ $layout['date']['y'] ?? 400 }}px; left: {{ $layout['date']['x'] ?? 300 }}px;">
        {{ \Carbon\Carbon::parse($cert->issued_date)->format('F d, Y') }}
    </div>

</div>

</body>
</html>
