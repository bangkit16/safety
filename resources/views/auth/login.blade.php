@extends('admin.layouts.app', ['class' => 'login-page', 'page' => 'Login Page', 'contentClass' => 'login-page'])

@section('content')
    {{-- <div class="col-md-10 text-center ml-auto mr-auto">
        <h3 class="mb-5">Log in to see how you can speed up your web development with out of the box CRUD for #User
            Management and more.</h3>
    </div> --}}
    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('login') }}">
            @csrf

            <div class="card card-login card-white">
                <div class="card-header">
                    <img src="{{ asset('white') }}/img/card-primary.png" alt="">
                    <h1 class="card-title ">{{ 'Log in' }}</h1>
                </div>
                <div class="card-body mt-5">
                    <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-email-85 me-2"></i>
                            </div>
                        </div>
                        <input type="email" name="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            placeholder="{{ 'Email' }}" value="{{ old('email') }}">
                        @include('admin.alerts.feedback', ['field' => 'email'])
                    </div>
                    <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-lock-circle me-2"></i>
                            </div>
                        </div>
                        <input id="password" type="password" placeholder="{{ 'Password' }}" name="password"
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                        <div class="input-group-append ">
                            <div class="input-group-text ">
                                <i class="far fa-eye ps-2" id="togglePassword" style="cursor; color:black"></i>
                            </div>
                        </div>
                        @include('admin.alerts.feedback', ['field' => 'password'])
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" href=""
                        class="btn btn-primary btn-lg btn-block mb-3">{{ 'Get Started' }}</button>
                    <div class="pull-left">
                        {{-- <h6>
                            <a href="{{ route('register') }}" class="link footer-link">{{ 'Create Account' }}</a>
                        </h6> --}}
                    </div>
                    <div class="pull-right">
                        <h6>
                            <a href="{{ route('password.request') }}"
                                class="link ">{{ 'Forgot password?' }}</a>
                        </h6>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@stack('js')
<script>
    window.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            // Toggle type attribute untuk password
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // Toggle ikon mata (eye) dan mata tertutup (eye-slash)
            this.classList.toggle("fa-eye-slash");
            this.classList.toggle("fa-eye");
        });
    });
</script>
