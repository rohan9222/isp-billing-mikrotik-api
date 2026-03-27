<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>
  <style>
    .boopText{
  position: absolute;
  top: 50%;
  left: 50%;
}
html,body{
  background-color: black;
  overflow: hidden;
  margin: 0;
  margin-left: -1px;
  margin-top: -1px;
}
#modes{
  color: white;
  padding: 10px;
  border: solid thin white;
  font-size: 10pt;
  float: left;
  margin: 0;
}
  </style>
</head>
<body>

<div id='404wrap'>
  
  
</div>
<div id='modes'><input type='checkbox' id='usefade'> Use flashing text?<br />
<input type="number" id="life" min="100" max="5000" value="1000" step="100"> Text life
</div>

</body>
<script>
  window.setInterval(function(){
  var life = $("#life").val();
  var top = randNum();
  var left = randNum();
  var size = randomSize();
  var id = top.toString() + left.toString();
  if($("#usefade").is(':checked')){
    $("#404wrap").append("<span id='" + id + "' style='color:" + randomColor() + ";font-size:" + size + "pt;top:" + top + "%;left:" + left + "%;' class='boopText'>" + randomMessage() + "</span>").hide().fadeIn(150);
  } else {
    $("#404wrap").append("<span id='" + id + "' style='color:" + randomColor() + ";font-size:" + size + "pt;top:" + top + "%;left:" + left + "%;' class='boopText'>" + randomMessage() + "</span>");
  }
  setTimeout(function(){
    $("#" + id).remove();
  }, life);
}, 200);

function randomMessage() {
  messages = ['Oh No! 404', '404', 'aw snap, wrong page', '404 error!', 'We got lost!', "You're not supposed to be here!","You're lost!", 'Wrong page!', '404 - page not found', 'not found!','Whoops!'];
  return messages[Math.floor(Math.random() * messages.length)];
}

function randomColor(){
  return '#'+(Math.random()*0xFFFFFF<<0).toString(16);
}
function randomSize(){
  return Math.floor(Math.random()*(60-5+1)+1);
}
function randNum(){
    return Math.floor(Math.random()*(95-5+1)+1);
}
</script>
</html>
