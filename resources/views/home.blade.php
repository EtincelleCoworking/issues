@extends('layouts.app')

@section('content')
<div class="container">
    <issue />
</div>
@endsection


@section('javascript')
    <script type="application/javascript">
        const app = new Vue({
            el: '#app',
            data: {
            }
        });
    </script>
@endsection

