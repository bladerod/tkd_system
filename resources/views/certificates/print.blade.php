<!DOCTYPE html>
<html>
<head>
    <title>Print Certificates</title>
</head>
<body onload="window.print()">

@foreach($certs as $cert)
    <div style="page-break-after: always; text-align:center;">
        <h1>{{ $cert->title }}</h1>
        <h2>{{ $cert->student->student_name }}</h2>
        <p>Type: {{ $cert->certificate_type }}</p>
        <p>Date: {{ $cert->issued_date }}</p>
    </div>

@endforeach

</body>
</html>
