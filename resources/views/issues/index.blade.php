@extends('layouts.app')

@section('content')
    <div class="container">
        <issues :issues="issues"/>
    </div>
@endsection


@section('javascript')
    <script type="application/javascript">
        const app = new Vue({
            el: '#app',
            data: {
                api_token : '{{Auth::user()->api_token}}',
                issues: {!! json_encode($issues) !!}
            }
        });
    </script>
@endsection
