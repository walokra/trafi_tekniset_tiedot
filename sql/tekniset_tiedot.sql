DROP TABLE IF EXISTS tekniset_tiedot;

CREATE TABLE tekniset_tiedot (
  ajoneuvoluokka varchar(255),
  ensirekisterointipvm varchar(255),
  ajoneuvoryhma integer,
  ajoneuvonkaytto varchar(255),
  kayttoonottopvm integer,
  vari varchar(255),
  ovienLukumaara integer,
  korityyppi varchar(255),
  ohjaamotyyppi integer,
  istumapaikkojenLkm integer,
  omamassa integer,
  teknSuurSallKokmassa integer,
  tieliikSuurSallKokmassa integer,
  ajonKokPituus integer,
  ajonLeveys integer,
  ajonKorkeus integer,
  kayttovoima varchar(255),
  iskutilavuus integer,
  suurinNettoteho numeric,
  sylintereidenLkm integer,
  ahdin varchar(255),
  merkkiSelvakielinen varchar(255),
  mallimerkinta varchar(255),
  vaihteisto varchar(255),
  vaihteidenLkm integer,
  kaupallinenNimi varchar(255),
  voimanvalJaTehostamistapa varchar(255),
  tyyppihyvaksyntanro varchar(255),
  yksittaisKayttovoima varchar(255),
  kunta varchar(255), Co2 integer,
  jarnro BIGINT NOT NULL PRIMARY KEY UNIQUE
);

CREATE INDEX merkkiSelvakielinen_idx ON tekniset_tiedot(merkkiSelvakielinen);

CREATE INDEX mallimerkinta_idx ON tekniset_tiedot(mallimerkinta);
