SELECT REPLACE(ttcibd001111.t_item, ' ', '') AS id, ttcibd001111.t_dsca AS description,
    CASE (ttcibd001111.t_kitm)
        WHEN 1 THEN 'Purchased'
        WHEN 2 THEN 'Manufactured'
        WHEN 3 THEN ''
        WHEN 4 THEN 'Cost'
        WHEN 10 THEN 'List'
        END AS item_type, ttcibd001111.t_seak AS search_key, ttcibd001111.t_citg AS item_group, ttcmcs023111.t_dsca AS item_group_desc, ttcibd001111.t_cuni AS unit
FROM ttcibd001111, ttcmcs023111
WHERE ttcibd001111.t_citg LIKE '%3M' AND ttcibd001111.t_citg = ttcmcs023111.t_citg;