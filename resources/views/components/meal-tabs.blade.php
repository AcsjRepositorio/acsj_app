@props(['mealsByDay' => collect()])

@php
    $daysOfWeek = ['segunda'=>'Segunda','terça'=>'Terça','quarta'=>'Quarta','quinta'=>'Quinta','sexta'=>'Sexta'];
@endphp

<div class="meal-tabs-background shadow p-3 mb-5 bg-body rounded">
    <div class="text-center mt-3 mb-5">
        <h1 style="color:rgba(11, 11, 11, 0.6)">Menu da semana</h1>
    </div>
    <div class="meal-tabs-container">
        <div class="tabs-header">
            @foreach($daysOfWeek as $dayKey => $dayLabel)
                <button 
                    onclick="openDay(event, '{{ $dayKey }}')"
                    id="btn-{{ $dayKey }}"
                    class="tab-button"
                >
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
                            @if($meal->category_id === 2)
                                <x-cards.meal-week :meal="$meal" />
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="shadow-lg p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 350px; width: 100%;">
                        <img src="/images/cheff.png" alt="sem menu disponível" class="img-fluid empty-cart-img" style="max-height: 140px; object-fit: contain;">
                        <h5 class="mt-3 text-secondary">Para já, não temos pratos para este dia.</h5>
                        <h5 class="mb-3 text-secondary">Os nossos chefs estão a preparar uma ementa deliciosa.</h5>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Script para manter a aba ativa após o refresh -->
<script>
    function openDay(evt, dayName) {
        // Salva a aba ativa no localStorage
        localStorage.setItem('activeTab', dayName);

        // Esconde todos os conteúdos
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.style.display = 'none';
        });
        
        // Remove a classe 'active' de todos os botões
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active');
        });
        
        // Exibe a aba atual
        document.getElementById(dayName).style.display = 'block';
        evt.currentTarget.classList.add('active');
    }

    // Ao carregar o documento, lê a aba ativa do localStorage ou usa a padrão 'segunda'
    document.addEventListener('DOMContentLoaded', () => {
        const activeTab = localStorage.getItem('activeTab') || 'segunda';
        const activeButton = document.getElementById('btn-' + activeTab);
        if (activeButton) {
            activeButton.click();
        }
    });
</script>

<style>
    .meal-tabs-background {
        width: 82%;
        border-radius: 8px;       
        padding: 16px;            
        box-sizing: border-box;   
        margin: 0 auto;           
    }
    .meal-tabs-container {
        width: 100%;
        max-width: 1200px; 
        margin: 0 auto;
    }
    .tabs-header {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        justify-content: center;
    }
    .tab-button {
        color: #fff;
        padding: 8px 16px;
        border: none;
        background-color: #c6c6c6;
        cursor: pointer;
        border-radius: 4px;
        font-weight: bold;
    }
    .tab-button.active,
    .tab-button:hover {
        background-color: #00C49A;
    }
    .tab-content {
        display: none;
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
