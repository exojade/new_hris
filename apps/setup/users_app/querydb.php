<?php
/*
 * Author: Dahir Muhammad Dahir
 * Date: 26-April-2020 12:41 AM
 * About: this file is responsible
 * for all Database queries
 */

namespace fingerprint;
// require_once("../core/Database.php");

function setUserFmds($user_id, $index_finger_fmd_string, $middle_finger_fmd_string){
    // $myDatabase = new Database();
    // dump($index_finger_fmd_string);
    query("update tblusers set fingerprint = ? where Employeeid = ?", $index_finger_fmd_string, $user_id);
    // $sql_query = "update users set indexfinger=?, middlefinger=? where id=?";
    // $param_type = "ssi";
    // $param_array = [$index_finger_fmd_string, $middle_finger_fmd_string, $user_id];
    // $affected_rows = $myDatabase->update($sql_query, $param_type, $param_array);
    return "success";
}

function getUserFmds($user_id){
    // $myDatabase = new Database();
    $fmds = query("select fingerprint from tblusers where Employeeid=?", $user_id);
    // $param_type = "i";
    // $param_array = [$user_id];
    // $fmds = $myDatabase->select($sql_query, $param_type, $param_array);
    return json_encode($fmds);
}

function getUserDetails($user_id){
    // $myDatabase = new Database();
    $sql_query = query("select username, username as fullname from tblusers where Employeeid=?",$user_id);
    // $param_type = "i";
    // $param_array = [$user_id];
    // $user_info = $myDatabase->select($sql_query, $param_type, $param_array);
    return json_encode($sql_query);
}

function getAllFmds(){
    // $myDatabase = new Database();
    $allFmds = query("select fingerprint from tblusers where fingerprint != ''");
    // $sql_query = "select indexfinger from users where indexfinger != ''";
    // $allFmds = $myDatabase->select($sql_query);
    return json_encode($allFmds);
}