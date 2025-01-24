<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $user->name }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <!-- Editar Perfil -->
        <li>
            <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile.edit') }}" style="padding: 5px 10px;">
                <span>Editar Perfil</span>
                <i class="bi bi-gear" style="width: 20px; text-align: center;"></i>
            </a>
        </li>

        <!-- Logout -->
        <li>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between" style="padding: 5px 10px;">
                    <span>Logout</span>
                    <i class="bi bi-box-arrow-right" style="width: 20px; text-align: center;"></i>
                </button>
            </form>
        </li>

        <!-- Dashboard para admin -->
        @if ($user->user_type === 1)
        <li>
            <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('adminpanel.manage.order') }}" style="padding: 5px 10px;">
                <span>Dashboard </span>
                <i class="bi bi-speedometer2" style="width: 20px; text-align: center;"></i>
            </a>
        </li>
        @endif
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" ></script >