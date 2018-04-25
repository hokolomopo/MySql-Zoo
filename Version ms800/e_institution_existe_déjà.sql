Select rue, code_postal, pays, count(*) as existe_déjà
from Institution
where nom = :nom;