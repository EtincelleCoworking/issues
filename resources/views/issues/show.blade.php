@extends('layouts.app')

@section('content')
    <div class="container">
        <issue :issue="issue"/>
    </div>
@endsection


@section('javascript')
    <script type="application/javascript">
        const app = new Vue({
            el: '#app',
            data: {
                api_token : '{{Auth::user()->api_token}}',
                issue: {!! json_encode($issue) !!}
            }
        });
    </script>
@endsection
