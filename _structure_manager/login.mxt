<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>LuxBum Manager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="_styles/manager.css" />
  </head>

  <body>

    <h1><span>LuxBum Manager</span></h1>

    <div id="login">
      <div mXattribut="id:message_id">
        <p><mx:text id="message"/></p>
      </div>
      <form mXattribut="action:action" method="post" id="login_form" name="login_form">
        <p>
          <label for="username" class="float_login"><strong>Identifiant&nbsp;:</strong></label>
          <input name="username" id="username" type="text" maxlength="32" mXattribut="value:username_value" tabindex="1" />
        </p>
        <p>
          <label for="password" class="float_login"><strong>Mot de passe&nbsp;:</strong></label>
          <input name="password" id="password" type="password" mXattribut="value:password_value" tabindex="2" />
        </p>
        <p><input class="submit" type="submit" value="Ok" /></p>
      </form>
    </div>

    <script type="text/javascript">
      document.forms['login_form']['username'].focus();
    </script>

  </body>
</html>
