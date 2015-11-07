DROP TABLE IF EXISTS koodisto;

create table koodisto (id BIGINT NOT NULL PRIMARY KEY UNIQUE, koodistonkuvaus varchar(255), koodintunnus varchar(255), lyhytselite varchar(255), pitkaselite varchar(1000), kieli varchar(255));
	
CREATE INDEX koodisto_idx ON koodisto(koodintunnus);
