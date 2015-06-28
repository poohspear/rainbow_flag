<?php
/*
 * Copyright Phyo Zaw Tun
 * Licensed under MIT
 * wwww.phyozawtun.com
 * wwww.github.com/poohspear/
 */

// Generate profile image.
function generate_profile_image($q){
    // Get image file name.
    $pride_flag = "pride_flag.png";
    $profile_image = $q;
    // Get image info.
    $pride_flag_info = getimagesize($pride_flag);
    $profile_image_info = getimagesize($profile_image);
    // Check valid image or not.
    if($profile_image_info === FALSE)
    {
        $content = "<p><span class=\"text text-success\">Your uploaded file is not image file.</span><br />Pleaes try again</p>" . form();
        return page_template($content);
    }
    // Define flag image object.
    $pride_flag_obj = imagecreatefrompng($pride_flag);
    // Define profile image object.
    switch ($profile_image_info[2]) {
      case IMAGETYPE_GIF  : $profile_image_obj = imagecreatefromgif($profile_image);  break;
      case IMAGETYPE_JPEG : $profile_image_obj = imagecreatefromjpeg($profile_image); break;
      case IMAGETYPE_PNG  : $profile_image_obj = imagecreatefrompng($profile_image);  break;
    }
    // Get new sizes of flag base on profile image.
    list($width, $height) = $pride_flag_info;
    list($newwidth, $newheight) = $profile_image_info;
    // Resizse news flag.
    $new_resize_image = imagecreatetruecolor($newwidth, $newheight);
    $source = $pride_flag_obj;
    imagecopyresized($new_resize_image, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // Set pride_flag_obj as new image.
    $pride_flag_obj = $new_resize_image;
    // Image marge
    imagealphablending($pride_flag_obj, false);
    imagesavealpha($pride_flag_obj, true);
    imagecopymerge($pride_flag_obj, $profile_image_obj, 0, 0, 0, 0, $newwidth, $newheight, 50);
    imagepng($pride_flag_obj,"profile.png");
    $content = "    
                    <p>
                        <img src=\"profile.png\" class=\"img-responsive\">
                        <div class=\"clearfix\"></div>
                        <span class=\"text text-success\">Your image save successfully.</span><br />
                        Try with new anotjer again
                    </p>" . form();
    return page_template($content);
}
// Main template.
function page_template($content){
    return "
    <!DOCTYPE html>
    <html lang=\"en\">
      <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Rainbow Flag</title>
        <!-- Bootstrap -->
        <link href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css\" rel=\"stylesheet\">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
          <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.\"></script>
        <![endif]-->
      </head>
      <body>
        <div class=\"container\">
            <div class=\"col-md-6 col-md-offset-3\">
                <h1>Rainbow flag photo maker</h1>
                {$content}
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js\"></script>
      </body>
    </html>
    ";
}
// Submit form.
function form(){
    return "
        <form method=\"post\" action=\"index.php\" enctype=\"multipart/form-data\" role=\"form\">
            <div class=\"form-group\">
                <label>Please choose an image file.</label>
                <input type=file name=\"profile_image\" class=\"form-control\"/>
            </div>
            <div class=\"form-group\">
                <input type=\"submit\" class=\"form-control btn btn-success\" value=\"Generate Image\"/>
            </div>
        </form>
    ";
}
// Define the content
if(!isset($_FILES['profile_image'])){
    $content = form();
    echo page_template($content);
}else{
    $q = $_FILES['profile_image']['tmp_name'];
    echo generate_profile_image($q);
}
?>
