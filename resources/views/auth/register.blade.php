@extends('auth.layouts.master')

@section('css')
<style>
    .password-field {
        position: relative
    }

    .password-field .btn {
        position: absolute;
        top: 0px;
        right: 0px;
    }

</style>
@endsection
@section('content')
<div class="card-body">
    {{-- @if($errors->any())
{{dd($errors->all())}}
    @endif --}}
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
        <p class="text-center small">Enter your personal details to create account</p>
    </div>

    <form class="row g-3 needs-validation" novalidate method="POST" action="{{route('student.register')}}">
        @csrf
        {{-- name --}}
        <div class="col-12">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{old('name')}}">
            @if ($errors->has('name'))
            <span class="text-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
        {{-- email --}}
        <div class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{old('email')}}">
            @if ($errors->has('email'))
            <span class="text-danger">{{$errors->first('email')}}</span>
            @endif
        </div>

        {{-- password --}}
        <div class="col-12">
            <label for="password" class="form-label">Password</label>
            <div class="password-field">
                <input type="password" name="password" class="form-control" id="password">
                <button type="button" class="btn btn-transparent toggle-password" data-target="password">
                    <i class="far fa-eye"></i>
                </button>
            </div>
            @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>

        {{-- confirm password --}}
        <div class="col-12">
            <label for="password" class="form-label">Enter Confirm Password</label>
            <div class="password-field">
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                <button type="button" class="btn btn-transparent toggle-password" data-target="password_confirmation">
                    <i class="far fa-eye"></i>
                </button>
            </div>
            @if ($errors->has('password_confirmation'))
            <span class="text-danger">{{$errors->first('password_confirmation')}}</span>
            @endif
        </div>

        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit">Create Account (Register)</button>
        </div>
        <div class="col-12">
            <p class="small mb-0">Already have an account? <a href="#">Log in</a></p>
        </div>
    </form>

</div>
@endsection


@section('script')
<script>
    // =========toggle password==============
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.password-field').forEach(function(field) {
            const passwordInput = field.querySelector('input[type="password"]');
            const toggleButton = field.querySelector('.toggle-password');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleButton.querySelector('i').classList.toggle('fa-eye');
                toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    });
    // ========================================

</script>



@endsection
