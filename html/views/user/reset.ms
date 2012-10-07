{{#error}}<div class="alert alert-error">{{error}}</div>{{/error}}
<form action="{{form.action}}" method="POST">
    <input type="hidden" name="_token" value="{{form._token}}">
    <input type="text" name="username" placeholder="Username" value="{{form.username}}">
    or 
    <input type="text" name="email" placeholder="Email Address" value="{{form.email}}">
    <input type="submit" name="submit" />
</form>