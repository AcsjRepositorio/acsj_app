@extends('adminlte::page')

@section('title', 'Dashboard - Inserir usuário')

@section('content')

<div class="container mb-5" style="width: 70%; max-width: 800px;">
    <h1 class="mb-3">Inserir novo usuário</h1>
    <h5>No painel abaixo, você pode editar as informações dos pratos disponíveis no menu da semana</h5>
    <ul>
        <li>Atualize apenas os campos necessários, revisando os dados antes de confirmar.</li>
        <li>Para concluir, clique em "Salvar Alterações".</li>
        <li>Se preferir cancelar as mudanças, utilize a opção "Voltar" para retornar ao dashboard sem aplicar nenhuma modificação.</li>
    </ul>
</div>

<div class="container p-4 bg-light rounded shadow-sm" style="max-width: 700px;">
    <!-- Multi-Step Form -->
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
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
           
            
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Insira o nome do usuário" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Insira o email" required>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-success" id="nextBtn">Próximo</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step-2" class="form-step d-none">
            
        <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Insira a senha" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirme a Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme a senha" required>
            </div>

            <div class="text-center d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevBtn">Voltar</button>
                <button type="button" class="btn btn-success" id="nextBtnStep2">Próximo</button>
            </div>
        </div>

        <!-- Step 3 -->
        <div id="step-3" class="form-step d-none">
         

            <div class="form-group">
                <label for="user_type">Tipo de Usuário</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="1">Admin</option>
                    <option value="2">Cliente</option>
                </select>
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
