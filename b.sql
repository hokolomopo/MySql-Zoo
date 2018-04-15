SELECT * 
FROM 
(
	Animal 
	NATURAL JOIN 
	(
		SELECT nom_scientique, n_puce, count(distinct n_registre) as nb_intervention 
		FROM Intervention 
		GROUP BY nom_scientique, n_puce
	) as t1
) 
ORDER BY nb_intervention;
