@extends('chat::layouts.master')

@section('content')
    <h1>Hello World</h1>
     <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>

    <p>
        This view is loaded from module: {!! config('chat.name') !!}
    </p>
@endsection
