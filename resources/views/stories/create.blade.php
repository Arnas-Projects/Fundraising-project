<h1>Sukurkite istoriją</h1>

<form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
    @csrf

    <input type="text" name="title" placeholder="Pavadinimas">

    <textarea name="short_description" placeholder="Trumpas aprašymas"></textarea>

    <textarea name="full_story" placeholder="Pilnas aprašymas"></textarea>

    <input type="number" name="goal_amount" placeholder="Tikslo suma">

    <input type="file" name="main_image">

    <button type="submit">Sukurti</button>

</form>

<a href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
