<h2>Create Certificate Template</h2>

<form method="POST" action="/templates/store">
@csrf

<input type="text" name="name" placeholder="Template Name" required><br><br>

<select name="type">
    <option value="promotion">Belt Promotion</option>
    <option value="competition">Competition</option>
</select>

<br><br>

<button type="submit">Save Template</button>

</form>
