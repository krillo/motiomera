use `motiomera`;

DELETE FROM mm_lag WHERE foretag_id NOT IN(SELECT id FROM mm_foretag);