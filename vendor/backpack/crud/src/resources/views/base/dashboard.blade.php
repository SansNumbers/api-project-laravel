@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
@endphp

@section('content')
<div class="alert alert-primary" role="alert">
    Welcome to admin panel!
</div>

<div style="color: purple   " class="alert alert-success" role="alert">
    Admin panel provides you :
    <ul class="list-group list-group-flush">
        <li  class="list-group-item" style="color: red">1. Create a new users</li>
        <li class="list-group-item" style="color: orange">2. Create a new posts</li>
        <li class="list-group-item" style="color: blue">3. Create a new categories</li>
        <li class="list-group-item" style="color: green">4. Create a new comments</li>
    </ul>
</div>
@endsection
