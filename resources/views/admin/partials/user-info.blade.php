<div class="user-info">
    <div class="image">
        <img src="/admin/images/user.png" width="48" height="48" alt="User" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</div>
        <div class="email">{{auth()->user()->email}}</div>
        <div class="btn-group user-helper-dropdown">
            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
            <ul class="dropdown-menu pull-right">
                <li><a href="{{route('admin.profile')}}"><i class="material-icons">person</i>Profile</a></li>
                {{-- <li role="seperator" class="divider"></li> --}}
                {{-- <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li> --}}
                {{-- <li><a href="{{route('admin.orders')}}"><i class="material-icons">shopping_cart</i>Sales</a></li> --}}
                {{-- <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li> --}}
                <li role="seperator" class="divider"></li>
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="material-icons">input</i>
                        {{ __('Sign Out') }}
                    </a>
                </li>
            </ul>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>