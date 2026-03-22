@extends('main')

@section('content')
    <h1>Redaguoti tagą</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Pavadinimas:</label>
            <input type="text" name="name" value="{{ $tag->name }}" required>
        </div>
        <button type="submit">Atnaujinti</button>
        <a href="{{ route('admin.tags.index') }}">Grįžti į tagų sąrašą</a>
    </form>
@endsection

{{-- @section('content')
<div class="container mt-5">
    <h1>Manage Hashtags</h1>

    <!-- Add New Tag Form -->
    <form action="{{ route('admin.tags.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="New hashtag name" required>
            <button type="submit" class="btn btn-primary">Add Hashtag</button>
        </div>
    </form>

    <!-- Existing Tags List -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>
                        <!-- Delete Tag Form -->
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this hashtag?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection --}}