<!-- Componente dropdown com o nome do User -->
<div class="dropdown">
    <button
        class="btn btn-secondary dropdown-toggle"
        type="button"
        id="dropdownMenuButton"
        data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ $user->name }}
    </button>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding-left: 0;">
        <!-- Editar Perfil -->
        <li>
            <a 
                class="dropdown-item d-flex align-items-center justify-content-between"
                href="{{ route('profile.edit') }}"
                style="padding: 5px 10px;">
                <span>Editar Perfil</span>
                <i class="bi bi-gear" style="width: 20px; text-align: center;"></i>
            </a>
        </li>

        <!-- Logout -->
        <li>
    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
        @csrf
    </form>
    <a 
        class="dropdown-item d-flex align-items-center justify-content-between"
        href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        style="padding: 5px 10px;">
        <span>Logout</span>
        <i class="bi bi-box-arrow-right" style="width: 20px; text-align: center;"></i>
    </a>
</li>


        <!-- Dashboard -->
        @if (auth()->user()->user_type === 1)
        <li>
            <a 
                class="dropdown-item d-flex align-items-center justify-content-between"
                href="{{ route('dashboard') }}"
                style="padding: 5px 10px;">
                <span>Dashboard</span>
                <i class="bi bi-speedometer2" style="width: 20px; text-align: center;"></i>
            </a>
        </li>
        @endif
    </ul>
</div>
