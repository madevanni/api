SELECT t_orno, t_cotp, t_odat, t_otbp, t_sorn, 
CASE (t_hdst)
WHEN 10 THEN 'Approved'
WHEN 25 THEN 'Closed'
WHEN 30 THEN 'Canceled'
END AS Status FROM ttdpur400111;