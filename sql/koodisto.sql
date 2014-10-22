create table koodisto (id integer INTEGER PRIMARY KEY ASC NOT NULL UNIQUE, koodistonkuvaus string, koodintunnus string, lyhytselite string, pitkaselite string, kieli string);

.separator "|"

.import koodisto.csv koodisto

CREATE INDEX koodisto_idx ON koodisto(koodintunnus);
