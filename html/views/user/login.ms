{{#error}}<div class="alert alert-error">{{error}}</div>{{/error}}
<form action="{{form.action}}" method="POST">
    <input type="hidden" name="_token" value="{{form._token}}">
    <input type="text" name="username" placeholder="Username" value="{{form.username}}">
    <input type="password" name="password" placeholder="Password" value="">
    <input type="submit" name="submit" />
</form>
<a class="btn" data-bypass href="{{form.link_to.facebook}}">Sign in with Facebook</a> |
<a class="btn" data-bypass href="{{form.link_to.twitter}}">Sign in with Twitter</a> |
<a class="btn" data-bypass href="{{form.link_to.google}}">Sign in with Google</a>

</body>
</html>
