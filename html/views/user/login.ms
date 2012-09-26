
<!DOCTYPE html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <title>Auth Test</title>
<body>
	
<form class="form-inline" action="{{login_check}}" method="post">
  {{#error}}<div class="alert alert-error">{{error}}</div>{{/error}}
  <input type="text" name="_username" value="{{last_username}}" placeholder="Username" />
  <input type="password" name="_password" value="" placeholder="Password" />
  <button type="submit" class="btn">Sign in</button>
  <a class="btn" data-bypass href="{{login_facebook}}">Sign in with Facebook</a>
</form>


</body>
</html>
