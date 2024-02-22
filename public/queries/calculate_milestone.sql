DELIMITER $$

USE `panabohrmdb`$$

DROP FUNCTION IF EXISTS `calculate_milestone`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `calculate_milestone`(emp_id VARCHAR(255), overall_service INT, current_year INT) RETURNS INT(11)
BEGIN
    DECLARE milestone_year INT;

    -- Check if there is an "Active" status
    SELECT MAX(
        CASE 
            WHEN overall_service > 10 THEN YEAR(DATE) + 5  -- +5 years from the latest "Active" status
            ELSE YEAR(DATE) + 10  -- +10 years from the latest "Active" status
        END
    ) INTO milestone_year
    FROM employee_continuous_year
    WHERE employee_id = emp_id AND STATUS = 'Active' AND DATE IS NOT NULL;

    -- If no "Active" status found, use the original calculation based on the hire date
    IF milestone_year IS NULL THEN
        SELECT MAX(
            CASE 
                WHEN overall_service > 10 THEN YEAR(DATE) + 5  -- +5 years from the latest "Active" status
                ELSE YEAR(DATE) + 10  -- +10 years from the latest "Active" status
            END
        ) INTO milestone_year
        FROM employee_continuous_year
        WHERE employee_id = emp_id AND DATE IS NOT NULL;
    END IF;
    
    WHILE milestone_year < current_year DO
        SET milestone_year = milestone_year + 5;  -- Increment by 5 years
    END WHILE;

    RETURN milestone_year;
END$$

DELIMITER ;