@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="container chooser-container">
            <div class="session-message-container">
                <p class="session-message">
                    This system is open in another window or device.
                    Click <strong>«Use Here»</strong> to use it in this window.
                </p>
                <a href="#" class="btn btn-danger"
                   onclick="document.getElementById('logout_form').submit();">Sign out</a>
                <a href="#" class="btn btn-primary"
                   onclick="document.getElementById('loginMultipleForm').submit();">Use Here</a>
            </div>
            <form class="form-horizontal" id="loginMultipleForm" role="form" method="POST"
                  action="{{ route('login.continue') }}">
                {{ csrf_field() }}
                <input id="continue" type="text" hidden name="continue" required value="yes"/>
            </form>
            {!! Form::open(['url' => 'logout','method' => 'POST','id'=>'logout_form']) !!}
            {{ csrf_field() }}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
