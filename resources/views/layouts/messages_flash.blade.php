@if($message = Session::get('danger'))
    <div class="alert aler-danger">{{$message}}</div>
    @endif

@if($message = Session::get('succes'))
    <div class="alert alert-succes">{{$message}}</div>
    @endif