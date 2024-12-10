@extends('admin.layouts.app', ['class' => 'register-page', 'page' => 'Register Page', 'contentClass' => 'register-page'])

@section('content')
    <div class="row">
        <div class="col-md-5 ml-auto mb-5">
            <div class="info-area info-horizontal mt-5">
                <div class="icon icon-warning">
                    <i class="tim-icons icon-wifi"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ 'Marketing' }}</h3>
                    <p class="description">
                        {{ 'We\'ve created the marketing campaign of the website. It was a very interesting collaboration.' }}
                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-primary">
                    <i class="tim-icons icon-triangle-right-17"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ 'Fully Coded in HTML5' }}</h3>
                    <p class="description">
                        {{ 'We\'ve developed the website with HTML5 and CSS3. The client has access to the code using GitHub.' }}
                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-info">
                    <i class="tim-icons icon-trophy"></i>
                </div>
                <div class="description">
                    <h3 class="info-title">{{ 'Built Audience' }}</h3>
                    <p class="description">
                        {{ 'There is also a Fully Customizable CMS Admin Dashboard for this product.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-7 mr-auto">
            <div class="card card-register card-white">
                <div class="card-header">
                    <img class="card-img" src="{{ asset('white') }}/img/card-primary.png" alt="Card image" style="width: 500px; height: 280px">
                    <h4 class="card-title">{{ 'Register' }}</h4>
                </div>
                <form class="form" method="post" action="{{ route('register') }}">
                    @csrf
                    <div class="card-body">
                        <div class="input-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-single-02"></i>
                                </div>
                            </div>
                            <input type="text" name="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ 'Name' }}" value="{{ old('name') }}" value="{{ old('name')}}">
                            @include('admin.alerts.feedback', ['field' => 'name'])
                        </div>
                        <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-email-85"></i>
                                </div>
                            </div>
                            <input type="email" name="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ 'Email' }}" value="{{ old('email') }}" value="{{ old('email')}}">
                            @include('admin.alerts.feedback', ['field' => 'email'])
                        </div>
                        <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="{{ 'Password' }}" >
                            @include('admin.alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="{{ 'Confirm Password' }}">
                        </div>
                        <div class="form-check text-left {{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label class="form-check-label">
                                <input
                                    class="form-check-input {{ $errors->has('agree_terms_and_conditions') ? ' is-invalid' : '' }}"
                                    name="agree_terms_and_conditions" type="checkbox"
                                    {{ old('agree_terms_and_conditions') ? 'checked' : '' }}>
                                <span class="form-check-sign"></span>
                                {{ 'I agree to the terms and conditions' }}
                                @include('admin.alerts.feedback', [
                                    'field' => 'agree_terms_and_conditions',
                                ])
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-danger btn-round btn-lg">{{ 'Get Started' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
