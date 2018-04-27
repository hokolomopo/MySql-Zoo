SELECT n_registre, nom, prenom FROM
(SELECT DISTINCT n_registre, n_enclos FROM  Entretien NATURAL JOIN Technicien) AS T2
NATURAL JOIN Personnel
GROUP BY n_registre
HAVING COUNT(n_enclos) = (SELECT COUNT(*) FROM Enclos);
