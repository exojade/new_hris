<?php
/*
 * Author: Dahir Muhammad Dahir
 * Date: 26-April-2020 5:44 PM
 * About: identification and verification
 * will be carried out in this file
 */

namespace fingerprint;

require("querydb.php");
require_once("includes/core/helpers/helpers.php");
// print_r($_POST);
//     exit();
if(!empty($_POST["data"])) {
    // dump($_POST);
    $user_data = json_decode($_POST["data"]);
    // dump($user_data);

    $user_id = $user_data->id;
    //this is not necessarily index_finger it could be
    //any finger we wish to identify
    $pre_reg_fmd_string = $user_data->index_finger[0];

    $hand_data = json_decode(getUserFmds($user_id));
    $enrolled_fingers = [
        "index_finger" => $hand_data[0]->fingerprint,
        "middle_finger" => $hand_data[0]->fingerprint
    ];

    // dump($pre_reg_fmd_string);

    $json_response = verify_fingerprint($pre_reg_fmd_string, $enrolled_fingers);
    // dump($json_response);
    $response = json_decode($json_response);
    // dump($response);

    if($response === "match"){
        echo getUserDetails($user_id);
    }
    else{
        echo json_encode("failed");
    }
}

else{
    echo "post request with 'data' field required";
}
