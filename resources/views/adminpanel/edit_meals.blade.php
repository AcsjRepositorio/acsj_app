@extends('adminlte::page')

@section('title', 'Dashboard - Editar refeição')

@section('content')

<div class="container mb-5" style="width: 70%; max-width: 800px;">
    <h1 class="mb-3">Editar pratos do Cardápio</h1>
    <h5>No painel abaixo, você pode editar as informações dos pratos disponíveis no menu da semana</h5>
    <ul>
        <li>Atualize apenas os campos necessários, revisando os dados antes de confirmar.</li>
        <li>Para concluir, clique em "Salvar Alterações".</li>
        <li>Se preferir cancelar as mudanças, utilize a opção "Voltar" para retornar ao dashboard sem aplicar nenhuma modificação.</li>
    </ul>
</div>

<div class="container p-4 bg-light rounded shadow-sm" style="max-width: 700px;">
    <!-- Multi-Step Form -->
   

        <!-- Step Indicators -->
        <div class="d-flex justify-content-center align-items-center mb-4">
            <ul class="steps d-flex list-unstyled justify-content-center gap-2 align-items-center">
                <li class="step active">
                    <span class="step-circle">1</span>
                </li>
                <li class="step-separator"></li>
                <li class="step">
                    <span class="step-circle">2</span>
                </li>
                <li class="step-separator"></li>
                <li class="step">
                    <span class="step-circle">3</span>
                </li>
            </ul>
        </div>

        <form action="{{ route('meals.update', $meal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Step 1 -->
        <div id="step-1" class="form-step">
            <!-- Avatar -->


            <div class="mb-4 text-center">
                <div class="bg-secondary rounded" style="width: 120px; height: 120px; margin: 0 auto;">
                    <img id="photoPreview"
                        src="{{ $meal->photo ? asset('storage/' . $meal->photo) : asset('images/default-meal.jpg') }}"
                        alt="Foto do Prato"
                        class="img-fit rounded-circle border"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <input type="file" name="photo" class="form-control mt-3" accept="image/*" onchange="previewPhoto(event)">

            </div>

            <div class="mb-4">
                <label for="name" class="form-label">Nome do prato</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $meal->name) }}"
                    class="form-control" required>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-success" id="nextBtn">Próximo</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step-2" class="form-step d-none">
            <div class="mb-4">
                <label for="price" class="form-label">Preço</label>
                <input type="number" name="price" id="price" value="{{ old('price', $meal->price) }}"
                    class="form-control" step="0.01" required>

            </div>

            <div class="mb-4">
                <label for="category_id" class="form-label">Tipo de Refeição</label>
                <select name="category_id" id="category_id" class="form-select" required>

                    @foreach ($categories as $categoryName => $categoryId)
                    <option value="{{ $categoryId }}" {{ old('category_id', $meal->category_id) == $categoryId ? 'selected' : '' }}>
                        {{ $categoryName }}
                    </option>
                    @endforeach



                </select>
            </div>


            @if(isset($meals) && isset($meals->day_of_week))
            <td class="bg-black text-white justify-content-center p-1" style="height: 50px; margin-right: 5px;">
                <p class="text-center m-0" style="writing-mode: vertical-rl; font-size: 12px;">
                    {{ ucfirst($meals->day_of_week) }}
                </p>
            </td>
            @endif
             




            <div>
                <label for="day_week_start">Data de venda:</label>
                <input type="date" name="day_week_start" id="day_week_start"
                    value="{{ old('day_week_start', $meal->day_week_start) }}" required>
            </div>


            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevBtn">Voltar</button>
                <button type="button" class="btn btn-success" id="nextBtnStep2">Próximo</button>
            </div>
        </div>

        <!-- Step 3 -->
        <div id="step-3" class="form-step d-none">
            <div class="mb-4">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $meal->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevBtnStep3">Voltar</button>
                <button type="submit" class="btn btn-success">Salvar Alterações </button>
            </div>
        </div>
    </form>
</div>

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection

@section('css')
<style>
    .steps-container {
        display: flex;
        justify-content: center;
    }

    .steps {
        display: flex;
        align-items: center;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dee2e6;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: #6c757d;
    }

    .active .step-circle {
        background-color: #198754;
        color: #fff;
    }

    .form-step {
        padding: 10px 20px;
    }
</style>
@endsection

@section('js')
<script>
    const steps = document.querySelectorAll('.form-step');
    const stepCircles = document.querySelectorAll('.step-circle');
    let currentStep = 0;

    function updateStepIndicator(step) {
        stepCircles.forEach((circle, index) => {
            if (index <= step) {
                circle.parentNode.classList.add('active');
            } else {
                circle.parentNode.classList.remove('active');
            }
        });
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        steps[currentStep].classList.add('d-none');
        currentStep++;
        steps[currentStep].classList.remove('d-none');
        updateStepIndicator(currentStep);
    });

    document.getElementById('nextBtnStep2').addEventListener('click', () => {
        steps[currentStep].classList.add('d-none');
        currentStep++;
        steps[currentStep].classList.remove('d-none');
        updateStepIndicator(currentStep);
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        steps[currentStep].classList.add('d-none');
        currentStep--;
        steps[currentStep].classList.remove('d-none');
        updateStepIndicator(currentStep);
    });

    document.getElementById('prevBtnStep3').addEventListener('click', () => {
        steps[currentStep].classList.add('d-none');
        currentStep--;
        steps[currentStep].classList.remove('d-none');
        updateStepIndicator(currentStep);
    });

    function previewPhoto(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection