<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">   
  <title>Drive flasher</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="upload-container">
    <form>
      <div class="form-group">
        <input type="file" name="file" id="file">
        <label for="file">Choose a file</label>
      </div>
      <input type="submit" value="Upload" method=POST enctype=multipart/form-data  onclick="uploadFile()">
    </form>
  </div>
  <div class="drive-selection-container">
    <table class="responsive">
      <tr>
        <th>Drive</th>
        <th>Size (GB)</th>
        <th>Flash drive</th>
      </tr>
      <?php
        exec('lsblk -d --output NAME,SIZE', $output);
        foreach ($output as $line) {
          if (preg_match('/^(.*)\s+(\d+[G|M])$/', $line, $matches)) {
            $drive = $matches[1];
            $size = preg_replace('/[GgMm]/', '', $matches[2]);
            if (preg_match('/[Mm]/', $matches[2])) {
              $size = $size / 1024;
            }
            echo "<tr><td>$drive</td><td>$size</td><td><input type='checkbox'></td></tr>\n";
          }
        }
      ?>
    </table>
  </div>



  <script>
    function uploadFile() {
      event.preventDefault();
      var file = document.getElementById("file").files[0];
      var formData = new FormData();
      formData.append("file", file);
      
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "upload.php", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log("File uploaded successfully!");
          } else {
            console.log("File upload failed, status code: " + xhr.status);
          }
        }
      };
      xhr.send(formData);
    }

  </script>

</body>
</html>
