@extends('layout')
@section('content')
    <form action="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input name="name" value="{{ old('name') }}" type="text" class="form-control{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
            @if($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input name="email" value="{{ old('email') }}" type="text" class="form-control{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
            @if($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Password</label>
            <input name="password" value="" type="password" class="form-control{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
            @if($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Retyped Password</label>
            <input name="password_confirmation" value="" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
@endsection
