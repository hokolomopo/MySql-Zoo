Select COUNT(*) as bon_animal
from Animal
where nom_scientifique = :nom_scientifique and n_puce = :n_puce;