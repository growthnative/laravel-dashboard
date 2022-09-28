<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
               
                    @can('dashboard-read')
                        <a class="nav-link {{ (request()->is('admin/dashboard*')) ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                    @endcan
                
                    @can('role-read')
                        <a class="nav-link {{ (request()->is('roles*')) ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Manage Roles
                        </a>
                    @endcan
                
                    @can('sub-user-read')
                        <a class="nav-link {{ (request()->is('users*')) ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Manage Users
                        </a>
                    @endcan
              
                    @if( Auth::user()->id == 1)
                        <a class="nav-link {{ (request()->is('permission*')) ? 'active' : '' }}" href="{{ route('permission.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Policies
                        </a>
                    @endif  
                    
                    @can('payment-read')
                        <a class="nav-link {{ (request()->is('stripe*')) ? 'active' : '' }}" href="{{ route('stripeDetails') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Payment
                        </a>

                        <a class="nav-link {{ (request()->is('payment-setting*')) ? 'active' : '' }}" href="{{ route('payment-setting.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Payment Settings
                        </a>
                    @endcan  
                    
                    
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                {{ ucfirst(Auth::user()->first_name.' '.Auth::user()->last_name) ?? ''}}
            </div>
        
    </nav>
</div>