
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
    }
    else
    {
        if(isset($_GET["action"])):
            
            
        else:
            $retirement = query('
            SELECT
            Employeeid,
            FirstName,
            LastName,
            BirthDate,
            DATE_ADD(BirthDate, INTERVAL 65 YEAR) AS EffectivityDate
        FROM
            tblemployee
        WHERE
            YEAR(BirthDate) + 65 = YEAR(CURDATE())
            AND active_status = 1
            AND JobType IN ("PERMANENT", "COTERMINOUS", "CASUAL")
        ORDER BY
            EffectivityDate ASC;
            ');



            render("apps/records/records_app/reports.php", 
                ["title" => "DASHBOARD",
                 "retirement" => $retirement,
                ],"records");
        endif;
       


        
    }
?>