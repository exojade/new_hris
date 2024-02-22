DELIMITER $$

USE `panabohrmdb`$$

DROP FUNCTION IF EXISTS `calculate_overall_service`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `calculate_overall_service`(emp_id VARCHAR(255), emp_date DATE) RETURNS INT(11)
BEGIN
    DECLARE hire_year INT;
    DECLARE current_year INT;

    SELECT YEAR(emp_date) INTO hire_year;
    SELECT YEAR(CURDATE()) INTO current_year;

    RETURN IF(current_year - hire_year < 0, 0, current_year - hire_year);
END$$

DELIMITER ;