@extends('layouts.app')

@section('content')
    <div class="container">
        <issue :issue="issue" :current_user="user" />
    </div>
@endsection


@section('javascript')
    <script type="application/javascript">
        window.app = new Vue({
            el: '#app',
            data: {
                user : {!! json_encode(Auth::user()->makeVisible('api_token')) !!},
                issue: {!! json_encode($issue) !!}
            }
        });
    </script>
@endsection
