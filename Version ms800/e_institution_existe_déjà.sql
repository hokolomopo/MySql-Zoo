Select rue, code_postal, pays, count(*) as existe
from Institution
where nom = :nom;