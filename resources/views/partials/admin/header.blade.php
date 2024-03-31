<header class="bg-dark px-2 d-flex justify-content-between align-items-center text-light">
    <h1><a class="text-decoration-none text-light" href="{{ route('admin.admin-list') }}">WorldSkills</a></h1>
    <nav>
        <ul class="list-unstyled d-flex gap-5">
            <li>
                <a
                    class="text-light text-decoration-none"
                    href="{{ route('admin.admin-list') }}"
                >Admins</a>
            </li>
            <li>
                <a
                    class="text-light text-decoration-none"
                    href="{{ route('admin.user-list') }}"
                >Users</a>
            </li>
            <li>
                <a
                    class="text-light text-decoration-none"
                    href="{{ route('admin.game-list') }}"
                >Games</a>
            </li>
        </ul>
    </nav>
    <div class="d-flex gap-2 align-items-center">
        <p class="m-0 p-1 text-bg-light rounded">
            {{ \Illuminate\Support\Facades\Auth::guard('admin')->user()->username }}
        </p>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Log Out</button>
        </form>
    </div>
</header>
