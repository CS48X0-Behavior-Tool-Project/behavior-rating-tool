<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Upload Test</title>
  </head>
  <body>

    <!-- Extremely simple form that lets you upload test .csv files to read into the webpage -->
    <form action="/upload" method="post" enctype="multipart/form-data">
      @csrf
      <input type="file" name="mycsv" id="mycsv">
      <input type="submit" value="Upload">
    </form>
  </body>
</html>
