<form method="POST">
@csrf

<h3>Students</h3>
@foreach($students as $s)
<input type="checkbox" name="student_ids[]" value="{{ $s->id }}">
{{ $s->name }} <br>
@endforeach

<h3>Template</h3>
<select name="template_id">
@foreach($templates as $t)
<option value="{{ $t->id }}">{{ $t->name }}</option>
@endforeach
</select>

<input type="text" name="belt_level" placeholder="Belt Level">

<button type="submit">Generate</button>
</form>
