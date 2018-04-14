SELECT n_registre FROM
(SELECT DISTINCT n_registre, n_enclos FROM  Entretien NATURAL JOIN Technicien) AS T2
GROUP BY n_registre
HAVING COUNT(n_enclos) = (SELECT COUNT(*) FROM Enclos);
