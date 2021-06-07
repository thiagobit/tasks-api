@extends('emails.layout')

@section('body')
    <div>
        <p>The task {{ $task->title }} has being deleted.</p>
    </div>
@endsection
