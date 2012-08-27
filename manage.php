<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

include "conn.php";

include "header.php";
?>
<div class="row">
  <h3 class="span6">Links</h3>
</div>
<div class="row">
  <div class="span2"><a href="#">Consent Form</a></div>
  <div class="span2"><a href="#">Intro</a></div>
  <div class="span2"><a href="#">Pre-Q</a></div>
  <div class="span2"><a href="#">Post-Q</a></div>
</div>
<div class="row">
  <h3 class="span6">Sessions</h3>
</div>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>User ID</th>
      <th>Email</th>
      <th>Task1</th>
      <th>Task2</th>
      <th>Task3</th>
      <th>Scheduled at</th>
      <th>Data</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>101</td>
      <td>user01</td>
      <td>user01@mit.edu</td>
      <td><a href="list.php?tid=1&iid=1">1</a></td>
      <td><a href="list.php?tid=2&iid=2">2</a></td>
      <td><a href="list.php?tid=3&iid=3">3</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>102</td>
      <td>user02</td>
      <td>user02@mit.edu</td>
      <td><a href="list.php?tid=1&iid=1">1</a></td>
      <td><a href="list.php?tid=2&iid=3">3</a></td>
      <td><a href="list.php?tid=3&iid=2">2</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>103</td>
      <td>user03</td>
      <td>user03@mit.edu</td>
      <td><a href="list.php?tid=1&iid=2">2</a></td>
      <td><a href="list.php?tid=2&iid=1">1</a></td>
      <td><a href="list.php?tid=3&iid=3">3</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>104</td>
      <td>user04</td>
      <td>user04@mit.edu</td>
      <td><a href="list.php?tid=1&iid=2">2</a></td>
      <td><a href="list.php?tid=2&iid=3">3</a></td>
      <td><a href="list.php?tid=3&iid=1">1</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>105</td>
      <td>user05</td>
      <td>user05@mit.edu</td>
      <td><a href="list.php?tid=1&iid=3">3</a></td>
      <td><a href="list.php?tid=2&iid=1">1</a></td>
      <td><a href="list.php?tid=3&iid=2">2</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>106</td>
      <td>user06</td>
      <td>user06@mit.edu</td>
      <td><a href="list.php?tid=1&iid=3">3</a></td>
      <td><a href="list.php?tid=2&iid=2">2</a></td>
      <td><a href="list.php?tid=3&iid=1">1</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>      
    <tr>
      <td>107</td>
      <td>user07</td>
      <td>user07@mit.edu</td>
      <td><a href="list.php?tid=1&iid=1">1</a></td>
      <td><a href="list.php?tid=2&iid=2">2</a></td>
      <td><a href="list.php?tid=3&iid=3">3</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>108</td>
      <td>user08</td>
      <td>user08@mit.edu</td>
      <td><a href="list.php?tid=1&iid=1">1</a></td>
      <td><a href="list.php?tid=2&iid=3">3</a></td>
      <td><a href="list.php?tid=3&iid=2">2</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>109</td>
      <td>user09</td>
      <td>user09@mit.edu</td>
      <td><a href="list.php?tid=1&iid=2">2</a></td>
      <td><a href="list.php?tid=2&iid=1">1</a></td>
      <td><a href="list.php?tid=3&iid=3">3</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>110</td>
      <td>user10</td>
      <td>user10@mit.edu</td>
      <td><a href="list.php?tid=1&iid=2">2</a></td>
      <td><a href="list.php?tid=2&iid=3">3</a></td>
      <td><a href="list.php?tid=3&iid=1">1</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>111</td>
      <td>user11</td>
      <td>user11@mit.edu</td>
      <td><a href="list.php?tid=1&iid=3">3</a></td>
      <td><a href="list.php?tid=2&iid=1">1</a></td>
      <td><a href="list.php?tid=3&iid=2">2</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>
    <tr>
      <td>112</td>
      <td>user12</td>
      <td>user12@mit.edu</td>
      <td><a href="list.php?tid=1&iid=3">3</a></td>
      <td><a href="list.php?tid=2&iid=2">2</a></td>
      <td><a href="list.php?tid=3&iid=1">1</a></td>
      <td>9/1 3pm</td>
      <td><a href="#">link</a></td>
    </tr>                    
  </tbody>  
</table>

</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>

<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<script src="js/libs/log4javascript.js"></script>

<script type="text/javascript">    
    $(document).ready(function() {
      // Nav bar activation
      var task_id = <?php echo $task_id; ?>;
      var interface_id = <?php echo $interface_id; ?>;

    });
</script>
<script src="js/script.js"></script>
<!--
<script>
  var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
-->
</body>
</html>
