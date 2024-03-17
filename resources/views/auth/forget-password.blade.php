@extends('auth.layouts.master')

@section('title')
Forget Password
@endsection

@section('content')
<div class="card-body">
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Forget Password</h5>
        <p class="text-center small">Enter your email</p>
    </div>

    <form class="row g-3 needs-validation" novalidate method="POST" action="{{route('forgetPassword')}}">
        @csrf
        {{-- email --}}
        <div class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{old('email')}}">
            @if ($errors->has('email'))
            <span class="text-danger">{{$errors->first('email')}}</span>
            @endif
        </div>

        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit">Forget Password</button>
        </div>
        <div class="col-12">
            <p class="small mb-0">Don't have account? <a href="{{route('user.register')}}">Create an account</a></p>
        </div>
    </form>

</div>
@endsection



@section('script')
@endsection