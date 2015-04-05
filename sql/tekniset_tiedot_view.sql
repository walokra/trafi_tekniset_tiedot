drop view tekniset_tiedot_view;
create view tekniset_tiedot_view as select jarnro, 
	(select lyhytselite from koodisto where koodintunnus=ajoneuvoluokka and koodistonkuvaus='ajoneuvoluokka' and kieli='fi') as ajoneuvoluokka, 
	ensirekisterointipvm, ajoneuvoryhma, ajoneuvonkaytto, kayttoonottopvm, 
	(select lyhytselite from koodisto where koodintunnus=vari and koodistonkuvaus='Ajoneuvon väri' and kieli='fi') as vari, 
	ovienLukumaara, 
	(select lyhytselite from koodisto where koodintunnus=korityyppi and koodistonkuvaus LIKE 'Korityyppi' and kieli='fi') as korityyppi, 
	(select lyhytselite from koodisto where koodintunnus=ohjaamotyyppi::text and koodistonkuvaus='Ohjaamotyyppi' and kieli='fi') as ohjaamotyyppi, 
	istumapaikkojenLkm, omamassa, teknSuurSallKokmassa, tieliikSuurSallKokmassa, ajonKokPituus, ajonLeveys, ajonKorkeus, 
	(select lyhytselite from koodisto where koodintunnus=kayttovoima and koodistonkuvaus='Polttoaine' and kieli='fi') as kayttovoima, 
	iskutilavuus, suurinNettoteho, sylintereidenLkm, ahdin, merkkiSelvakielinen, mallimerkinta, vaihteisto, vaihteidenLkm, kaupallinenNimi, 
	(select lyhytselite from koodisto where koodintunnus=voimanvalJaTehostamistapa and koodistonkuvaus='Voimanvälitys ja tehostamistapa' and kieli='fi') as voimanvalJaTehostamistapa, 
	tyyppihyvaksyntanro, yksittaisKayttovoima, 
	(select lyhytselite from koodisto where koodintunnus=kunta and koodistonkuvaus='Kuntien numerot ja nimet' and kieli='fi') as kunta, 
	Co2 from tekniset_tiedot;

drop materialized view tekniset_tiedot_mat_view;
create view tekniset_tiedot_mat_view as select jarnro, 
	(select lyhytselite from koodisto where koodintunnus=ajoneuvoluokka and koodistonkuvaus='ajoneuvoluokka' and kieli='fi') as ajoneuvoluokka, 
	ensirekisterointipvm, ajoneuvoryhma, ajoneuvonkaytto, kayttoonottopvm, 
	(select lyhytselite from koodisto where koodintunnus=vari and koodistonkuvaus='Ajoneuvon väri' and kieli='fi') as vari, 
	ovienLukumaara, 
	(select lyhytselite from koodisto where koodintunnus=korityyppi and koodistonkuvaus LIKE 'Korityyppi' and kieli='fi') as korityyppi, 
	(select lyhytselite from koodisto where koodintunnus=ohjaamotyyppi::text and koodistonkuvaus='Ohjaamotyyppi' and kieli='fi') as ohjaamotyyppi, 
	istumapaikkojenLkm, omamassa, teknSuurSallKokmassa, tieliikSuurSallKokmassa, ajonKokPituus, ajonLeveys, ajonKorkeus, 
	(select lyhytselite from koodisto where koodintunnus=kayttovoima and koodistonkuvaus='Polttoaine' and kieli='fi') as kayttovoima, 
	iskutilavuus, suurinNettoteho, sylintereidenLkm, ahdin, merkkiSelvakielinen, mallimerkinta, vaihteisto, vaihteidenLkm, kaupallinenNimi, 
	(select lyhytselite from koodisto where koodintunnus=voimanvalJaTehostamistapa and koodistonkuvaus='Voimanvälitys ja tehostamistapa' and kieli='fi') as voimanvalJaTehostamistapa, 
	tyyppihyvaksyntanro, yksittaisKayttovoima, 
	(select lyhytselite from koodisto where koodintunnus=kunta and koodistonkuvaus='Kuntien numerot ja nimet' and kieli='fi') as kunta, 
	Co2 from tekniset_tiedot;
	
grant all on tekniset_tiedot_view to trafi;
grant all on tekniset_tiedot_mat_view to trafi;
