<x-guest-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="mb-4 text-center">Trocar Senha</h1>

                {{-- Mensagem de sucesso --}}
                @if (session('status') == 'password-updated')
                    <div class="alert alert-success" role="alert">
                        Senha atualizada com sucesso!
                    </div>
                @endif

                {{-- Mensagem de erros de validação --}}
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PATCH')

                    {{-- Campo de Senha Atual --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Campo de Nova Senha --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Campo de Confirmar Nova Senha --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Atualizar Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para alternar a visibilidade dos campos de senha --}}
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                var inputSelector = this.getAttribute('data-toggle');
                var input = document.querySelector(inputSelector);
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                } else {
                    input.setAttribute('type', 'password');
                    this.innerHTML = '<i class="bi bi-eye"></i>';
                }
            });
        });
    </script>
</x-guest-layout>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
