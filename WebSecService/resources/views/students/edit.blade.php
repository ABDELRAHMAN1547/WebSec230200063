<h1>Edit Student</h1>
<form method="POST" action="{{ url("/students/{$student->id}") }}">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $student->name }}"><br>
    <input type="email" name="email" value="{{ $student->email }}"><br>
    <input type="number" name="age" value="{{ $student->age }}"><br>
    <button type="submit">Update</button>
</form>
