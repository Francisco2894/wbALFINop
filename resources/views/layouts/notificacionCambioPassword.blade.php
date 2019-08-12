@if (session('mensaje'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
        <li>{!! session('mensaje') !!}</li>
        </ul>
    </div>
@endif