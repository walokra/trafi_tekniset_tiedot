create table tekniset_tiedot (ajoneuvoluokka text, ensirekisterointipvm varchar(255), ajoneuvoryhma integer, ajoneuvonkaytto text, kayttoonottopvm date, vari integer, ovienLukumaara integer, korityyppi text, ohjaamotyyppi integer, istumapaikkojenLkm integer,  omamassa integer, teknSuurSallKokmassa integer, tieliikSuurSallKokmassa integer, ajonKokPituus integer, ajonLeveys integer, ajonKorkeus integer, kayttovoima text, iskutilavuus integer, suurinNettoteho integer, sylintereidenLkm integer, ahdin text, merkkiSelvakielinen text, mallimerkinta text, vaihteisto text, vaihteidenLkm integer, kaupallinenNimi integer, voimanvalJaTehostamistapa text, tyyppihyvaksyntanro text, yksittaisKayttovoima text, kunta text, Co2 integer, jarnro integer PRIMARY KEY ASC NOT NULL UNIQUE);

.separator "|"
.import data.csv tekniset_tiedot

CREATE INDEX merkkiSelvakielinen_idx ON tekniset_tiedot(merkkiSelvakielinen);

CREATE INDEX mallimerkinta_idx ON tekniset_tiedot(mallimerkinta);
