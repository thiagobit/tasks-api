@extends('emails.layout')

@section('body')
    <div>
        <p>A new task has being created: <a href="{{ route('v1.users.tasks.show', [$user, $task]) }}">take a look</a>.</p>
    </div>
@endsection
