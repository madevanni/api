SELECT ttccom100110.t_bpid AS id, ttccom100110.t_nama AS customer, ttccom130110.t_namc AS address, ttccom130110.t_pstc AS zipcode, ttccom130110.t_ccit AS city, ttccom130110.t_ccty AS country, ttccom130110.t_telp AS telephone,
CASE (ttccom100110.t_bprl)
WHEN 1 THEN 'unknown'
WHEN 2 THEN 'customer'
WHEN 3 THEN 'supplier'
WHEN 4 THEN 'customer and supplier'
END AS role,
CASE (ttccom100110.t_prst)
WHEN 1 THEN 'inactive'
WHEN 2 THEN 'active'
END AS status FROM ttccom100110, ttccom130110 WHERE  ttccom100110.t_cadr = ttccom130110.t_cadr;