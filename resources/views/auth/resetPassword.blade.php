@extends('auth.layouts.master')

@section('title')
Reset Password
@endsection

@section('content')
<div class="card-body">
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>
        <p class="text-center small">Enter new password</p>
    </div>

    <form class="row g-3 needs-validation" novalidate method="POST" action="{{route('resetPassword')}}">
        @csrf
        <input type="hidden" name="id" value="{{$user[0]['id']}}">

       {{-- password --}}
       <div class="col-12">
        <label for="password" class="form-label">Password</label>
        <div class="password-field">
            <input type="password" name="password" class="form-control">
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
            <input type="password" name="password_confirmation" class="form-control">
            <button type="button" class="btn btn-transparent toggle-password" data-target="password_confirmation">
                <i class="far fa-eye"></i>
            </button>
        </div>
        @if ($errors->has('password_confirmation'))
        <span class="text-danger">{{$errors->first('password_confirmation')}}</span>
        @endif
    </div>

        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit">Reset Password</button>
        </div>
        <div class="col-12">
            <p class="small mb-0">Don't have account? <a href="{{route('user.register')}}">Create an account</a></p>
        </div>
    </form>

</div>
@endsection



@section('script')
@endsection