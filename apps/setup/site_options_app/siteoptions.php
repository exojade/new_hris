
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "updateSiteOptions"):
            // dump($_POST);


            query("update site_options set
                    site_url = '".$_POST["site_url"]."',
                    google_clientID = '".$_POST["google_clientID"]."',
                    google_clientSecret = '".$_POST["google_clientSecret"]."',
                    google_redirectUri = '".$_POST["google_redirectUri"]."',
                    google_folder_id = '".$_POST["google_folder_id"]."',
                    google_full_id = '".$_POST["google_full_id"]."'
            ");


            $res_arr = [
                "status" => "success",
                "title" => "Success",
                "message" => "Success Updating Site Options",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();
        endif;
    
      
    }
    else
    {
        if(isset($_GET["action"])):
        else:
            $site_options = query("select * from site_options");
            render("apps/setup/site_options_app/site_options_form.php", 
                ["title" => "SITE OPTIONS",
                 "site_options" => $site_options[0],
                ],"setup");
        endif;
    }
?>