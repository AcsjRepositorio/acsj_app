
@props(['meal'])

<div style="border-radius: 50px 0px; border: 1px solid black; width: 265px ; height: 309px;">
    <div>
        <img
            src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                ? asset('storage/' . $meal->photo) 
                : asset('images/default-meal.jpg') }}"
            alt="Foto de {{ $meal->name }}"
            class="rounded-circle"
            style="width: 50px; height: 50px;">
    </div>
    <h3>{{ $meal->name }}</h3>
    <p>{{ $meal->description }}</p>
    <p><strong>Preço:</strong> R$ {{ number_format($meal->price, 2, ',', '.') }}</p>
</div>
