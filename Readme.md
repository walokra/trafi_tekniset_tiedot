# Haku TraFin Avoimen datan "ajoneuvojen tekniset tiedot" -aineistoon

Yksinkertainen hakusivusto TraFin ajoneuvojen teknisiä tietoja sisältävään pilottiaineistoon.

## Data

http://www.trafi.fi/palvelut/avoin_data

### Tietokanta

tekniset_tiedot:
sqlite> create table tekniset_tiedot (ajoneuvoluokka text, ensirekisterointipvm date, ajoneuvoryhma integer, ajoneuvonkaytto text, kayttoonottopvm date, vari integer, ovienLukumaara integer, korityyppi text, ohjaamotyyppi integer, istumapaikkojenLkm integer,  omamassa integer, teknSuurSallKokmassa integer, tieliikSuurSallKokmassa integer, ajonKokPituus integer, ajonLeveys integer, ajonKorkeus integer, kayttovoima text, iskutilavuus integer, suurinNettoteho integer, sylintereidenLkm integer, ahdin text, merkkiSelvakielinen text, mallimerkinta text, vaihteisto text, vaihteidenLkm integer, kaupallinenNimi integer, voimanvalJaTehostamistapa text, tyyppihyvaksyntanro text, yksittaisKayttovoima text, kunta text, Co2 integer, jarnro integer PRIMARY KEY ASC NOT NULL UNIQUE);
sqlite> .separator "|"
sqlite> .import data.csv tekniset_tiedot
sqlite> CREATE INDEX merkkiSelvakielinen_idx ON tekniset_tiedot(merkkiSelvakielinen);
sqlite> CREATE INDEX mallimerkinta_idx ON tekniset_tiedot(mallimerkinta);

koodisto:
sqlite> create table koodisto (id integer INTEGER PRIMARY KEY ASC NOT NULL UNIQUE, koodistonkuvaus string, koodintunnus string, lyhytselite string, pitkaselite string, kieli string);
sqlite> .separator "|"
sqlite> .import koodisto.csv koodisto
sqlite> CREATE INDEX koodisto_idx ON koodisto(koodintunnus);

tekniset_tiedot_view:
sqlite> create view tekniset_tiedot_view as select jarnro, (select lyhytselite from koodisto where koodintunnus=ajoneuvoluokka and koodistonkuvaus="Direktiivien mukainen kooditus, jossa huomioitu myös kansalliset ajoneuvoluokat." and kieli="fi") as ajoneuvoluokka, ensirekisterointipvm, ajoneuvoryhma, ajoneuvonkaytto, kayttoonottopvm, (select lyhytselite from koodisto where koodintunnus=vari and koodistonkuvaus="Ajoneuvon väri" and kieli="fi") as vari, ovienLukumaara, (select lyhytselite from koodisto where koodintunnus=korityyppi and koodistonkuvaus LIKE "%Korityyppi" and kieli="fi") as korityyppi, (select lyhytselite from koodisto where koodintunnus=ohjaamotyyppi and koodistonkuvaus="%Ohjaamotyyppi" and kieli="fi") as ohjaamotyyppi, istumapaikkojenLkm, omamassa, teknSuurSallKokmassa, tieliikSuurSallKokmassa, ajonKokPituus, ajonLeveys, ajonKorkeus, (select lyhytselite from koodisto where koodintunnus=kayttovoima and koodistonkuvaus="Polttoaine" and kieli="fi") as kayttovoima, iskutilavuus, suurinNettoteho, sylintereidenLkm, ahdin, merkkiSelvakielinen, mallimerkinta, vaihteisto, vaihteidenLkm, kaupallinenNimi, (select lyhytselite from koodisto where koodintunnus=voimanvalJaTehostamistapa and koodistonkuvaus="Voimanvälitys ja tehostamistapa" and kieli="fi") as voimanvalJaTehostamistapa, tyyppihyvaksyntanro, yksittaisKayttovoima, (select lyhytselite from koodisto where koodintunnus=kunta and koodistonkuvaus="Kuntien numerot ja nimet" and kieli="fi") as kunta, Co2 from tekniset_tiedot;

## Testisivu

http://dataoksi.fi/lab/trafi/tekniset-tiedot.php

# Lisenssi

sovellus: MIT

TraFin data: http://www.trafi.fi/palvelut/avoin_data/avoimen_datan_lisenssi

# Tekijä

Marko Wallin <mtw@iki.fi>
