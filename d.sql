Select nb_interv_animal_mauvais_enclos / nb_interv_tot as proportion
from 
(
    Select count(*) as nb_interv_tot
    from Intervention
) as t1
natural join
(
    Select count(*) as nb_interv_animal_mauvais_enclos
    from
    (
        Select nom_scientifique, n_puce
        from Intervention
    ) as t3
    natural join
    (
        Select nom_scientifique, n_puce, n_enclos
        from Animal
    ) as t4
    natural join
    (
        Select n_enclos, nom_climat
        from Enclos
    ) as t5
    where not exists
    (
        Select *
        from Climat
        where Climat.nom_scientifique = t3.nom_scientifique
          and Climat.nom_climat = t5.nom_climat
    )
) as t2
