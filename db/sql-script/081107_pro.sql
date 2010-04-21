use `motiomera`;

UPDATE mm_medlem
set levelId=1
WHERE id
IN (

SELECT medlem_id
FROM mm_order
WHERE medlem_id >0
AND expired =1
)
AND paidUntil > now( );
UPDATE mm_medlem
SET levelId=1
WHERE id
IN (

SELECT medlem_id
FROM mm_foretagsnycklar
WHERE medlem_id >0
)
AND paidUntil > now( )