@extends('adminlte::page')

@section('title', 'Dashboard - Inserir Refeição')

@section('content')

<div class="container mb-5" style="width: 70%; max-width: 800px;">
    <h1 class="mb-3">Inserir refeição no Cardápio</h1>
    <h5>No painel abaixo, você pode editar as informações dos pratos disponíveis no menu da semana</h5>
    <ul>
        <li>Atualize apenas os campos necessários, revisando os dados antes de confirmar.</li>
        <li>Para concluir, clique em "Salvar Alterações".</li>
        <li>Se preferir cancelar as mudanças, utilize a opção "Voltar" para retornar ao dashboard sem aplicar nenhuma modificação.</li>
    </ul>
</div>

<div class="container p-4 bg-light rounded shadow-sm" style="max-width: 700px;">
    <!-- Multi-Step Form -->
    <form class="was-validated" action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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

        <!-- Step 1 -->
        <div id="step-1" class="form-step">
            <!-- Foto da refeição -->
            <div class="mb-4 text-center">
                <div class="bg-secondary rounded" style="width: 120px; height: 120px; margin: 0 auto;">
                    <img src="{{ asset('images/default-meal.jpg') }}" alt="Foto padrão de refeição"
                        class="rounded-circle" style="width: 120px; height: 120px;">
                </div>
                <input type="file" name="photo" class="form-control mt-3" accept="image/*">
            </div>

            <div class="mb-4">
                <label for="name" class="form-label">Nome do prato</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-success" id="nextBtn">Próximo</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step-2" class="form-step d-none mb-5">
            <div style="width: 100%; display: flex; justify-content: space-between;">
                <!-- Campo para o Preço -->
                <div class="mb-4" style="width: 30%;">
                    <label for="price" class="form-label">Preço</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-control" step="0.01" required>
                    <div class="invalid-feedback">
                        Por favor, insira um preço válido.
                    </div>
                </div>
                <!-- Campo para o Estoque -->
                <div class="mb-4" style="width: 30%;" id="stock-container">
                    <label for="stock" class="form-label">Estoque</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" class="form-control" min="0" required>
                    <div class="invalid-feedback">
                        Por favor, insira a quantidade disponível.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="category_id" class="form-label">Tipo de Refeição</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="" selected disabled>Selecione um tipo de refeição</option>
                    @foreach ($categories as $categoryId => $categoryName)
                        <option value="{{ $categoryId }}" {{ old('category_id') == $categoryId ? 'selected' : '' }}>
                            {{ $categoryName }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Por favor, selecione uma categoria de refeição.
                </div>
            </div>

            <!-- Container do Datepicker: exibido somente para Almoço (id 2) -->
            <div class="mb-3" id="sale-date-container">
                <label for="day_week_start" class="form-label">Data de venda:</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="day_week_start" id="day_week_start">
                    <span class="input-group-text">
                        <i class="bi bi-calendar3"></i>
                    </span>
                    <input type="hidden" name="day_of_week" id="day_of_week">
                </div>
                <div class="mt-2">
                    <p id="dayOfWeek" class="text-muted"></p>
                </div>
                <div class="invalid-feedback">
                    Por favor, selecione uma data de venda!
                </div>
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
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                <div class="invalid-feedback">
                    Descreva um pouco do prato a ser oferecido.
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevBtnStep3">Voltar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
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

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    // Controle dos passos do formulário
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

    function validateStep(step) {
        const inputs = steps[step].querySelectorAll('input, select, textarea');
        let isValid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return isValid;
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            steps[currentStep].classList.add('d-none');
            currentStep++;
            steps[currentStep].classList.remove('d-none');
            updateStepIndicator(currentStep);
        }
    }

    function prevStep() {
        steps[currentStep].classList.add('d-none');
        currentStep--;
        steps[currentStep].classList.remove('d-none');
        updateStepIndicator(currentStep);
    }

    document.getElementById('nextBtn').addEventListener('click', nextStep);
    document.getElementById('nextBtnStep2').addEventListener('click', nextStep);
    document.getElementById('prevBtn').addEventListener('click', prevStep);
    document.getElementById('prevBtnStep3').addEventListener('click', prevStep);

    // Inicialização do datepicker
    $(function() {
        $("#day_week_start").datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(dateText) {
                const date = new Date(dateText);
                const dayOfWeek = date.toLocaleDateString('pt-PT', { weekday: 'long' });
                $('#dayOfWeek').text(`Dia da semana: ${dayOfWeek}`);
                $('#day_of_week').val(dayOfWeek);
            }
        });
    });

    // Exibir ou ocultar o container de data somente para Almoço (id 2)
    $(document).ready(function() {
        function toggleSaleDate() {
            var selectedCategory = $('#category_id').val();
            if (selectedCategory == "2") {
                $('#sale-date-container').show();
                $('#day_week_start').attr('required', true);
            } else {
                $('#sale-date-container').hide();
                $('#day_week_start').removeAttr('required');
            }
        }
        $('#category_id').on('change', toggleSaleDate);
        toggleSaleDate();
    });
</script>
@endsection
