Select COUNT(*) as bon_climat
from 
	Climat
natural join
(
	Select nom_climat
	from Enclos
	where n_enclos = :n_enclos
) as t1
where nom_scientifique = :nom_scientifique AND Climat.nom_climat = t1.nom_climat;