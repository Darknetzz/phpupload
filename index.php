<?php
function icon($svg, $size = 20) {
  return "<img src='img/svg/$svg.svg' style='width:$size;height:$size;'>";
}
 ?>
<html>
<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/progressbar.css">
</head>
<body class="bg-dark"><br>
  <div class="container">
    <div class="card">
  <div class="card-header bg-info">
    Upload
  </div>
  <div class="card-body bg-secondary">
    <!--<h5 class="card-title">Upload</h5>-->
    <p class="card-text" id="cardcontent">
      <form action='upload.php' id="uploadform" method='POST' enctype="multipart/form-data">
        <div class="form-group">
          <center><label for="fileToUpload" class="btn btn-success" style="color:white;"><?php echo icon('cloud-upload'); ?> Upload file...</label></center>
          <input type="file" name="fileToUpload" class="btn btn-success" id="fileToUpload" hidden>
          <input type="hidden" name="upload" value="1">
          <div id="progress-wrp">
              <div class="progress-bar"></div>
              <div class="status">0%</div>
          </div>
          <div id="response"></div>
        </div>
        <!--<input type="submit" name="upload" class="btn btn-primary" value="UPLOAD">-->
      </form>
    </p>
  </div>
</div>
<br><br>
<div class="card">
<div class="card-header bg-info">
Files uploaded
</div>
<div class="card-body bg-secondary">
<!--<h5 class="card-title">Upload</h5>-->
<p class="card-text" id="viewfiles">
<?php include_once("viewfiles.php"); ?>
</p>
</div>
</div>
</div>
</body>
</html>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/upload.js"></script>
<script>
//Change id to your id
$(document).ready(function() {
  $("#progress-wrp").hide();
  setInterval(function () {
    $.ajax({
     url:'viewfiles.php',
     type:'GET',
     success: function(data){
         $('#viewfiles').html(data);
     }
    });
  }, 3000);
});
$("#fileToUpload").on("change", function (e) {
    $("#progress-wrp").fadeIn();
    var file = $(this)[0].files[0];
    var upload = new Upload(file);

    // maby check size or type here with upload.getSize() and upload.getType()

    // execute upload
    upload.doUpload();
});
</script>
