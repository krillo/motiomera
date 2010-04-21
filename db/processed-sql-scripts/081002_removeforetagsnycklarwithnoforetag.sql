use `motiomera`;

DELETE FROM mm_foretagsnycklar WHERE foretag_id NOT IN(SELECT id FROM mm_foretag);