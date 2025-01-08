@props(['mealsByDay' => collect()])

@php
    $daysOfWeek = ['segunda'=>'Seg','terca'=>'Ter','quarta'=>'Qua','quinta'=>'Qui','sexta'=>'Sex'];
@endphp

<div class="meal-tabs-container">
    <div class="tabs-header">
        @foreach($daysOfWeek as $dayKey => $dayLabel)
            <button onclick="openDay(event, '{{ $dayKey }}')"
                    id="btn-{{ $dayKey }}"
                    class="tab-button">
                {{ $dayLabel }}
            </button>
        @endforeach
    </div>

    @foreach($daysOfWeek as $dayKey => $dayLabel)
        <div id="{{ $dayKey }}" class="tab-content">
            @php
                $meals = $mealsByDay[$dayKey] ?? collect();
            @endphp

            @if($meals->count() > 0)
                <div class="cards-wrapper">
                    @foreach($meals as $meal)
                        <x-cards.meal-week :meal="$meal" />
                    @endforeach
                </div>
            @else
                <p>Nenhum prato para este dia.</p>
            @endif
        </div>
    @endforeach
</div>

<!-- Script e CSS iguais ao que você já tem -->


<!-- Script para controlar a exibição das tabs -->
<script>
    function openDay(evt, dayName) {
        // 1. Esconde todos os conteúdos
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.style.display = 'none';
        });
        
        // 2. Remove a classe "active" de todos os botões
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active');
        });
        
        // 3. Mostra a tab atual e adiciona "active" ao botão que foi clicado
        document.getElementById(dayName).style.display = 'block';
        evt.currentTarget.classList.add('active');
    }

    // Exibe por padrão a primeira tab (por exemplo, segunda-feira)
    document.addEventListener('DOMContentLoaded', () => {
        const firstTabButton = document.getElementById('btn-segunda');
        if (firstTabButton) {
            firstTabButton.click();
        }
    });
</script>

<!-- CSS Básico de exemplo; personalize ao seu gosto -->
<style>
    .meal-tabs-container {
        width: 100%;
        margin: 0 auto;
    }

    .tabs-header {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        justify-content: center;
    }

    .tab-button {
        padding: 8px 16px;
        border: none;
        background-color: #eee;
        cursor: pointer;
        border-radius: 4px;
        font-weight: bold;
    }

    .tab-button.active,
    .tab-button:hover {
        background-color: #ccc;
    }

    .tab-content {
        display: none; /* Padrão: escondido */
        animation: fadeIn 0.3s ease-in-out;
    }

    .cards-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
</style>
