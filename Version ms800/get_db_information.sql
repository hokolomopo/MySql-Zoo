select column_name, data_type, character_maximum_length
from INFORMATION_SCHEMA.COLUMNS
where table_name = :table and column_name = :column;