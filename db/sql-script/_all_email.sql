USE `motiomera`;

select concat(trim(epost), ',',trim(fNamn), ',',trim(eNamn), ',',trim(fNamn), ' ',trim(eNamn)
)from mm_medlem where epostBekraftad = 1 and epost not like '%erendi%';