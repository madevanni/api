SELECT COUNT (*)
FROM ttcibd001111
    INNER JOIN ttcmcs023111
    ON ttcibd001111.t_citg = ttcmcs023111.t_citg
WHERE ttcibd001111.t_citg LIKE '%3M';