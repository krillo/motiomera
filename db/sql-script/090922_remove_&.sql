select companyName from mm_order where companyName like '%\&%'; 
update mm_order set companyName = replace(companyName, '\&amp;', '\&'); 
update mm_order set companyName = replace(companyName, 'amp;', ''); 
select companyName from mm_order where companyName like '%\&%';

select aNamn from mm_medlem where aNamn like '%\&%'; 
update mm_medlem set aNamn = replace(aNamn, '\&amp;', '\&'); 
update mm_medlem set aNamn = replace(aNamn, 'amp;', ''); 
select aNamn from mm_medlem where aNamn like '%\&%'; 


select namn from mm_foretag where namn like '%\&%'; 
update mm_foretag set namn = replace(namn, '\&amp;', '\&'); 
update mm_foretag set namn = replace(namn, 'amp;', ''); 
select namn from mm_foretag where namn like '%\&%';