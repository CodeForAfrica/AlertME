<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Confirm #Alerts Subscription</h2>

    <div>
      <p>Awesome you just subscribed for alerts on #GreenAlert!</p>
      <p>-{{$confirm_token}}-</p>
      <p>To start receivng alerts, visit this link: {{ URL::to('subscription/confirm', array($confirm_token)) }}.</p>
    </div>
  </body>
</html>