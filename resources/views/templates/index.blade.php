<h2>Certificate Templates</h2>

<a href="/templates/create">Create Template</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Type</th>
</tr>

@foreach($templates as $t)
<tr>
    <td>{{ $t->id }}</td>
    <td>{{ $t->name }}</td>
    <td>{{ $t->type }}</td>
    <td>
    <a href="/templates/{{ $t->id }}/editor">Edit</a>
</td>
</tr>
@endforeach
</table>
