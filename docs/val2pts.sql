SET sql_safe_updates=0;

ROLLBACK;
START TRANSACTION;

-- ------------------------------------------------------------------------------------------------------------------
-- Check for wrong points
-- ------------------------------------------------------------------------------------------------------------------

UPDATE results
SET points = NULL
WHERE points = "";

UPDATE records
SET points = NULL
WHERE points = "";

-- check for previously wrongly set inexistent events (points = -2) - Results
UPDATE results AS rs
JOIN events AS ev
    ON eventID = ev.ID
JOIN meetings AS mt
	ON meetingID = mt.ID
JOIN (
	SELECT DISTINCT typeID, name, distance, extra, gender, io, startDate, endDate
	FROM scoring AS sc
) AS sc
    ON sc.typeID = ev.typeID
    AND sc.name <=> ev.name
    AND sc.extra = ev.extra
    AND sc.gender = ev.gender
    AND sc.io = ev.io
    AND mt.startDate >= sc.startDate
	AND mt.startDate <= sc.endDate
SET rs.points = NULL
WHERE points = -2;

UPDATE records AS rc
JOIN (
	SELECT DISTINCT typeID, name, distance, extra, gender, io, startDate, endDate
	FROM scoring AS sc
) AS sc
    ON sc.typeID = rc.typeID
    AND sc.name <=> rc.name
    AND sc.extra = rc.extra
    AND sc.gender = rc.gender
    AND sc.io = rc.io
    AND rc.date >= sc.startDate
	AND rc.date <= sc.endDate
SET rc.points = NULL
WHERE points = -2;

-- ------------------------------------------------------------------------------------------------------------------
-- Set inexistent events
-- ------------------------------------------------------------------------------------------------------------------

-- set inexistent events as points = -2 - Results
UPDATE results AS rs
JOIN events AS ev
    ON eventID = ev.ID
JOIN meetings AS mt
	ON meetingID = mt.ID
LEFT JOIN (
	SELECT DISTINCT typeID, name, distance, extra, gender, io, startDate, endDate
	FROM scoring AS sc
) AS sc
    ON sc.typeID = ev.typeID
    AND sc.name <=> ev.name
    AND sc.extra = ev.extra
    AND sc.gender = ev.gender
    AND sc.io = ev.io
    AND mt.startDate >= sc.startDate
	AND mt.startDate <= sc.endDate
SET points = -2
WHERE points IS NULL
	AND sc.startDate IS NULL;

-- set inexistent records as points = -2 - 
UPDATE records AS rc
LEFT JOIN (
	SELECT DISTINCT typeID, name, distance, extra, gender, io, startDate, endDate
	FROM scoring AS sc
) AS sc
    ON sc.typeID = rc.typeID
    AND sc.name <=> rc.name
    AND sc.extra = rc.extra
    AND sc.gender = rc.gender
    AND sc.io = rc.IO
    AND rc.date >= sc.startDate
	AND rc.date <= sc.endDate
SET points = -2
WHERE points IS NULL
	AND sc.startDate IS NULL;





-- ------------------------------------------------------------------------------------------------------------------
-- Calculate points
-- ------------------------------------------------------------------------------------------------------------------

-- result = minResut ==> exact point
UPDATE results AS rs
JOIN events AS ev
    ON eventID = ev.ID
JOIN meetings AS mt
	ON meetingID = mt.ID
JOIN scoring AS sc
    ON sc.typeID = ev.typeID
    AND sc.name <=> ev.name
    AND sc.extra = ev.extra
    AND sc.gender = ev.gender
    AND minResultValue = resultValue
    AND sc.io = ev.io
    AND mt.startDate >= sc.startDate
	AND mt.startDate <= sc.endDate
SET rs.points = sc.points
WHERE rs.points IS NULL;

-- results < lowest mark ==> 0 points
UPDATE results AS rs
JOIN events AS ev
    ON eventID = ev.ID
JOIN meetings AS mt
	ON meetingID = mt.ID
JOIN (
        SELECT startDate, endDate, name, typeID, extra, gender, io,
            MIN(points) AS points, MIN(minResultValue) AS minResultValue
        FROM scoring AS sc
        WHERE points < 10
        GROUP BY name, typeID, extra, gender, io, startDate, endDate
    ) AS sc
    ON sc.typeID = ev.typeID
    AND sc.name <=> ev.name
    AND sc.extra = ev.extra
    AND sc.gender = ev.gender
    AND sc.io = ev.io
    AND mt.startDate >= sc.startDate
	AND mt.startDate <= sc.endDate
SET rs.points = 0
WHERE rs.points IS NULL
    AND resultValue < minResultValue;

UPDATE records AS rc
JOIN (
        SELECT startDate, endDate, name, typeID, extra, gender, io,
            MIN(points) AS points, MIN(minResultValue) AS minResultValue
        FROM scoring AS sc
        WHERE points < 10
        GROUP BY name, typeID, extra, gender, io, startDate, endDate
    ) AS sc
    ON sc.typeID = rc.typeID
    AND sc.name <=> rc.name
    AND sc.extra = rc.extra
    AND sc.gender = rc.gender
    AND sc.io = rc.io
    AND date >= sc.startDate
	AND date <= sc.endDate
SET rc.points = 0
WHERE rc.points IS NULL
    AND resultValue < minResultValue;

-- calculate points for valid results - Results
UPDATE results AS rs
	JOIN events AS ev
		ON eventID = ev.ID
	JOIN meetings AS mt
		ON meetingID = mt.ID
	JOIN scoring AS sc
		ON sc.typeID = ev.typeID
		AND sc.distance = ev.distance
		AND sc.name <=> ev.name
		AND sc.extra = ev.extra
		AND sc.gender = ev.gender
		AND sc.io = ev.io
		AND mt.startDate >= sc.startDate
		AND mt.startDate <= sc.endDate
		AND resultValue >= minResultValue
		AND resultValue < maxResultValue
SET rs.points = sc.points
WHERE rs.points IS NULL;
/*
-- re-calculate points for valid Walk results based on distance
UPDATE results AS rs
JOIN events AS ev
	ON eventID = ev.ID
JOIN meetings AS mt
	ON meetingID = mt.ID
JOIN scoring AS sc
	ON ev.typeID = 'W'
	AND sc.typeID = 'W'
	AND sc.distance = ev.distance
	AND sc.extra = ev.extra
	AND sc.gender = ev.gender
	AND sc.io = ev.io
	AND mt.startDate >= sc.startDate
	AND mt.startDate <= sc.endDate
	AND resultValue >= minResultValue
	AND resultValue < maxResultValue
SET rs.points = sc.points
WHERE rs.points IS NULL OR rs.points = -2;
*/
-- calculate points for valid results - Records
UPDATE records AS rc
JOIN scoring AS sc
	ON sc.typeID = rc.typeID
	AND sc.name <=> rc.name
	AND sc.name <=> rc.name
	AND sc.extra = rc.extra
	AND sc.gender = rc.gender
	AND sc.io = rc.io
	AND date >= sc.startDate
	AND date <= sc.endDate
	AND resultValue >= minResultValue
	AND resultValue < maxResultValue
SET rc.points = sc.points
WHERE (rc.points IS NULL OR rc.points = -2);

UPDATE records AS rc
JOIN scoring AS sc
	ON sc.typeID = rc.typeID
	AND sc.distance = rc.distance
	AND (sc.name <=> rc.name OR sc.typeID = 'W')
	AND sc.extra = rc.extra
	AND sc.gender = rc.gender
	AND sc.io = rc.io
	AND date >= sc.startDate
	AND date <= sc.endDate
	AND resultValue >= minResultValue
	AND resultValue < maxResultValue
SET rc.points = sc.points
WHERE (rc.points IS NULL OR rc.points = -2);

COMMIT;
ROLLBACK;

SET sql_safe_updates=1;

SELECT DISTINCT points, COUNT(ID)
FROM results
GROUP BY points
ORDER BY points DESC
LIMIT 10;

