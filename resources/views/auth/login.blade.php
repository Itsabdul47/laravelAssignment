
@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('adminlte::adminlte.login_message') }}</p>
                <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('adminlte::adminlte.email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="{{ __('adminlte::adminlte.password') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-8">
                            <!-- Remove the Remember Me checkbox -->
                            <!-- <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    {{ __('adminlte::adminlte.remember_me') }}
                                </label>
                            </div> -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('adminlte::adminlte.sign_in') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- <p class="mb-1">
                    <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}">
                        {{ __('adminlte::adminlte.i_forgot_my_password') }}
                    </a>
                </p> -->
                @if (config('adminlte.register_url', 'register'))
                    <!-- <p class="mb-0">
                        <a href="{{ url(config('adminlte.register_url', 'register')) }}" class="text-center">
                            {{ __('adminlte::adminlte.register_a_new_membership') }}
                        </a>
                    </p> -->
                @endif
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop