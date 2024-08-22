@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('manage.slider') }}" class="list-group-item">Manage Slider</a>
            </div>
        </div>
        <div class="col-md-9">
            <h1>Dashboard</h1>
        </div>
    </div>
</div>
@endsection
