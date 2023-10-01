SET sql_safe_updates=0;

ROLLBACK;
START TRANSACTION;

-- possible result fromats
-- s.00     electronic < 1 min or distance
-- s.0h     manual (3 conversions, split at 200m and 800m)
-- m:s.00   electronic > 1 min
-- m:s.0h   manual > 1 min (3 conversions, split at 200m and 800m)
-- m:s.0    > 1 min
-- h:m:s    > 1 hour
-- 0000     points
-- '-','DSQ','DNS','DNF','NT','ND','NOT' no result

-- UPDATE results SET resultValue = NULL;

UPDATE events
JOIN converteventname ON name = val
SET name = correct
WHERE NOT name <=> correct;

UPDATE events
JOIN converteventextra ON extra = val
SET extra = correct
WHERE NOT extra <=> correct;

UPDATE events
JOIN convertdistances ON name = val
SET distance = dist
WHERE distance != dist OR distance IS NULL;

UPDATE records
JOIN convertEventExtra
	ON extra = val
SET extra = correct
WHERE val != correct;

UPDATE records
JOIN convertDistances
	ON name = val
SET distance = dist
WHERE distance IS NULL;

-- distances
UPDATE events
SET distance = CAST(LEFT(name,LENGTH(name)-1) AS decimal)
WHERE name RLIKE '^[0-9]+m$'
AND distance IS NULL;

UPDATE events
SET distance = CAST(LEFT(name,LENGTH(name)-2) AS decimal)*1000
WHERE name RLIKE '^[0-9]+ *km$'
AND distance IS NULL;

UPDATE records
SET distance = CAST(name AS decimal)
WHERE name RLIKE '^[0-9]+m$'
AND distance IS NULL;

UPDATE records
SET distance = CAST(LEFT(name,LENGTH(name)-2) AS decimal)*1000
WHERE name RLIKE '^[0-9]+ *km$'
AND distance IS NULL;

UPDATE events SET distance = 400 WHERE name = '4x100m';
UPDATE events SET distance = 1600 WHERE name = '4x400m';
UPDATE events SET distance = 320 WHERE name = '4x80m';
UPDATE events SET distance = 240 WHERE name = '4x60m';
UPDATE events SET distance = 21000 WHERE typeID IN ('HM','HMC');
UPDATE events SET distance = 42000 WHERE typeID IN ('M','MC');

UPDATE events SET distance = 0 WHERE typeID IN ('YB','1H','24','HJ','PV','LJ','TJ','SP','DT','HT','JT','BT','10','08','07','05','04','03','TP');

UPDATE records SET distance = 400 WHERE name = '4x100m';
UPDATE records SET distance = 1600 WHERE name = '4x400m';
UPDATE records SET distance = 320 WHERE name = '4x80m';
UPDATE records SET distance = 240 WHERE name = '4x60m';
UPDATE records SET distance = 21000 WHERE typeID IN ('HM','HMC');
UPDATE records SET distance = 42000 WHERE typeID IN ('M','MC');

UPDATE records SET distance = 0 WHERE typeID IN ('YB','1H','24','HJ','PV','LJ','TJ','SP','DT','HT','JT','BT','10','08','07','05','04','03','TP');

-- already decimal 0.00
UPDATE results
SET resultValue = CAST(result AS DECIMAL(7,2))
WHERE result rlike '^[0-9]+\\.[0-9][0-9]$'
AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(result AS DECIMAL(7,2))
WHERE result rlike '^[0-9]+\\.[0-9][0-9]$'
AND resultValue IS NULL;

-- decimal 0.0h + 0.24 for dist <= 200m manual
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL(7,2)) + 0.24
WHERE result RLIKE '^[0-9]+\\.[0-9]h$'
    AND distance > 0
	AND distance <= 200
    AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL(7,2)) + 0.24
WHERE result RLIKE '^[0-9]+\\.[0-9]h$'
    AND distance > 0
	AND distance <= 200
    AND resultValue IS NULL;

-- decimal 0.0h + 0.14 for 200m < dist < 800m manual
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL(7,2)) + 0.14
WHERE result RLIKE '^[0-9]+\\.[0-9]h$'
    AND distance > 200
    AND distance < 800
    AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL(7,2)) + 0.14
WHERE result RLIKE '^[0-9]+\\.[0-9]h$'
    AND distance > 200
    AND distance < 800
    AND resultValue IS NULL;

-- 0:0.00 electronic
UPDATE results
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',result) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',result) AS TIME)) / 1000000
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9][0-9]$'
    AND resultValue IS NULL;

UPDATE records
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',result) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',result) AS TIME)) / 1000000
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9][0-9]$'
    AND resultValue IS NULL;

-- SET sql_mode = '';

-- 0:0.0h + 0.14 for 200m < dist < 800m manual
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = CAST(TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME)) / 1000000 + 0.14 AS DECIMAL(7,2))
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9]h$'
    AND distance > 200
    AND distance < 800
    AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME)) / 1000000 + 0.14 AS DECIMAL(7,2))
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9]h$'
    AND distance > 200
    AND distance < 800
    AND resultValue IS NULL;

-- 0:0.0h for dist >= 800m manual
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME)) / 1000000
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9]h*$'
    AND distance >= 800
    AND resultValue IS NULL;

UPDATE records
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
    + MICROSECOND(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME)) / 1000000
WHERE result RLIKE '^[0-9]+:[0-9]+\\.[0-9]h*$'
    AND distance >= 800
    AND resultValue IS NULL;

-- SET sql_mode = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

-- hours
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = TIME_TO_SEC(CAST(REPLACE(result,'h','') AS TIME))
WHERE result RLIKE ':.+:'
	AND (distance >= 10000 OR typeID = 'YB')
AND resultValue IS NULL;

UPDATE records
SET resultValue = TIME_TO_SEC(CAST(REPLACE(result,'h','') AS TIME))
WHERE result RLIKE ':.+:'
	AND distance >= 10000
AND resultValue IS NULL;

-- minutes
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
WHERE result RLIKE '[0-9][0-9]:[0-9][0-9]'
	AND distance >= 3000
AND resultValue IS NULL;

UPDATE records
SET resultValue = TIME_TO_SEC(CAST(CONCAT('0:',REPLACE(result,'h','')) AS TIME))
WHERE result RLIKE '[0-9][0-9]:[0-9][0-9]'
	AND distance >= 5000
AND resultValue IS NULL;

-- points
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = CAST(result AS DECIMAL)
WHERE result RLIKE '^[0-9]+$'
    AND typeID IN ('07','10','TP','YB','08','04','03','05')
    AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(result AS DECIMAL)
WHERE result RLIKE '^[0-9]+$'
    AND typeID IN ('07','10','TP','YB','08','04','03','05')
    AND resultValue IS NULL;

-- points manual
UPDATE results
JOIN events
    ON eventID = events.ID
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL)
WHERE result RLIKE '^[0-9]+h$'
    AND typeID IN ('07','10','TP','YB','08','04','03','05')
    AND resultValue IS NULL;

UPDATE records
SET resultValue = CAST(LEFT(result,LENGTH(result)-1) AS DECIMAL)
WHERE result RLIKE '^[0-9]+h$'
    AND typeID IN ('07','10','TP','YB','08','04','03','05')
    AND resultValue IS NULL;

-- no result
UPDATE results
SET resultValue = -1, points = -1
WHERE result IN ('-','DSQ','DNS','DNF','NT','ND','NOT','NM','DQ','NH','FS')
    AND resultValue IS NULL;
    
-- convert
UPDATE results
JOIN events
    on eventID = events.ID
JOIN eventtypes
    on typeID = eventtypes.ID
SET resultValue = -resultValue
WHERE sortDirection = 1
    AND resultValue > 0;

UPDATE records
JOIN eventtypes
    on typeID = eventtypes.ID
SET resultValue = -resultValue
WHERE sortDirection = 1
    AND resultValue > 0;

UPDATE results
JOIN events
	ON eventID = events.ID
SET results.heat = events.heat
WHERE results.heat IS NULL;

COMMIT;
ROLLBACK;

SET sql_safe_updates=1;

SELECT DISTINCT result
FROM results
WHERE resultValue IS NULL;
