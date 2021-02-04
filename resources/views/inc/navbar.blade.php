<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                @if(Auth::user()->contestNow() == null)
                @if(auth()->user()->level >= 2)
                <li class="nav-item">
                    <a class="nav-link" href="/runner">Runner</a>
                </li>
                @endif
                @if(auth()->user()->level >= 2)
                <li class="nav-item">
                    <a class="nav-link" href="/tasks/All">Tasks</a>
                </li>
                @endif
                @if(auth()->user()->level >= 3)
                <li class="nav-item">
                    <a class="nav-link" href="/lesson">Lesson</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contests">Contests</a>
                </li>
                @endif
                @if(auth()->user()->level >= 4)
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
                @endif
                @if(auth()->user()->level >= 2)
                <li class="nav-item">
                    <a class="nav-link" href="/submissions">Submissions</a>
                </li>
                @endif
                @if(auth()->user()->level >= 2)
                <li class="nav-item">
                    <a class="nav-link" href="/leaderboard">Leaderboard</a>
                </li>
                @endif
                @else
                <li class="nav-item">
                    <a class="nav-link" href="/contest/{{Auth::user()->contestNow()->contest->contest_id}}">Contest: {{Auth::user()->contestNow()->contest->name}}</a>
                </li>
                @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ __('Login') }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/redirect">{{ __('DGS Student/Staff') }}</a>
                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('Others') }}</a>
                        </div>
                    </li>
                @else
                    @if(Auth::user()->temp_level < Auth::user()->getRawOriginal('level') && Auth::user()->contestNow() == null)
                        <li class="nav-item">
                            <a class="nav-link text-danger text-small" href="/admin/reset_temp_level">
                                <small>Viewing as User Level {{auth()->user()->level)}}</small><br/>
                                <small>Click to Revert to Level {{auth()->user()->getRawOriginal("level")}}</small>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <li>
                            <a class="nav-link" href="/user/{{Auth::user()->name}}">
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <!--span class="caret"></span-->
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->contestNow() == null)
                                <a class="dropdown-item" href="/settings">
                                    {{ __('Settings') }}
                                </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>