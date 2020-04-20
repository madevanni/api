SELECT ttccom100111.t_bpid AS id, ttccom100111.t_nama AS customer, ttccom130111.t_namc AS address, ttccom130111.t_pstc AS zipcode, ttccom130111.t_ccit AS city, ttccom130111.t_ccty AS country, ttccom130111.t_telp AS telephone,
CASE (ttccom100111.t_bprl)
WHEN 1 THEN 'unknown'
WHEN 2 THEN 'customer'
WHEN 3 THEN 'supplier'
WHEN 4 THEN 'customer and supplier'
END AS role,
CASE (ttccom100111.t_prst)
WHEN 1 THEN 'inactive'
WHEN 2 THEN 'active'
WHEN 3 THEN 'inactive'
END AS status FROM ttccom100111, ttccom130111 WHERE  ttccom100111.t_cadr = ttccom130111.t_cadr;