@if(Auth::user()->is_admin)
    <p>Welcome, Admin!</p>
@else
    <p>You do not have admin access.</p>
@endif