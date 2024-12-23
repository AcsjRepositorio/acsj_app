@props(['meal'])

<div class="card">
        <div class="price-badge">€{{$meal->price}}</div>
        <img src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                ? asset('storage/' . $meal->photo) 
                : asset('images/default-meal.jpg') }}"
            alt="Foto de {{ $meal->name }}">
            
        <h3 class="card-title">{{$meal->name}}</h3>
        <p class="card-description">
            {{$meal->description}}
        </p>    

        <div class="d-flex justify-content-between align-items-center">
            <a type="button" class="button">Adicionar ao carrinho</a>
            <button type="button" class="btn btn-outline-secondary">ver mais</button>
        </div>

        <p> {{ ucfirst($meal->day_week_start) }}</p>


    </div>

<style>
      

        .card {
            width: 300px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            padding: 16px;
            text-align: center;
        }

        .card img {
            width: 100%;
            border-radius: 15px;
            margin-bottom: 16px;
        }

        .price-badge {
            position: absolute;
            top: -4.5px;
            right: -4px;
            background-color: #00C49A;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0;
        }

    

    .card-description {
    /* Altura definida */
    font-size: 14px;
    color: #6c757d;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 4; /* Limita o texto a 3 linhas */
    -webkit-box-orient: vertical;
    text-overflow: ellipsis; /* Adiciona as reticências ao texto cortado */
}

a{
    text-decoration: none;
}

.button{
border-radius: 8px;
padding: 6px;
/* background-color: #60B9FC;    */
background-color:#4A70E0;
color: #fff;
border:none;
text-decoration: none;
}

.button:hover{
    background-color: #6C757D;
}




    </style>

   











