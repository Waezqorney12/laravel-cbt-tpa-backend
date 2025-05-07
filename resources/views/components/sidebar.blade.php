<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Quizziz Web</a>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}" class="nav-link"><i
                        class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>

            <li>
                <a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-users"></i><span>Users</span></a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-question-circle"></i><span>Soal</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ route('soal.index') }}">Question</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('quiz.index') }}">Quizziz</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('materi.index') }}" class="nav-link"><i
                        class="fas fa-book"></i><span>Materi</span></a>
            </li>

            <li>
                <a href="{{ route('kelas.index') }}" class="nav-link"><i
                        class="fas fa-chalkboard"></i><span>Class</span></a>
            </li>
    </aside>
</div>
