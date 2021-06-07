@extends('emails.layout')

@section('body')
    <div>
        <p>A task has being updated: <a href="{{ route('v1.users.tasks.show', [$user, $task]) }}">take a look</a>.</p>
    </div>
@endsection
