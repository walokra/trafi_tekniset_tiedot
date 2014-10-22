# Ajoneuvojen tekniset tiedot - TraFi Avoin data

Yksinkertainen hakusivusto TraFin avoimen datan "ajoneuvojen tekniset tiedot" -pilottiaineistoon.

## TraFi avoin data 

Sovelluksessa käytetty data on saatavissa TraFin sivuilta: http://www.trafi.fi/tietopalvelut/avoin_data.

### Tietokanta

Ennen tietokantaan vientiä Excel-taulukkomuodossa olevaa dataa pitää hieman muokata. 1. Vaihdetaan kenttäerotin, koska datassa on desimaalilukuja. 2. Konvertoidaan UTF-8:ksi 3. Poistetaan ensimmäinen rivi, jossa on sarakkeiden nimet. Kenttäerotin on , joka vaihdetaan | -merkiksi, paitsi jos se on hipsujen sisällä.

1. awk 'BEGIN{FS=OFS="\""} {for (i=1;i<=NF;i+=2) gsub(/,/,"|",$i)}1' data.csv > data_clean.csv
2. iconv -f iso8859-15 -t utf8 data_clean.csv > data_utf8.csv
3. sed -i '1 d' data_utf8.csv

Kannan luonti ja avoimen datan importointi SQLiteen. Avataan kanta (sqlite3 trafi_ajotekn_utf8.sqlite), ja ajetaan seuraavat komennot:
- sql/tekniset_tiedot.sql
- sql/koodisto.sql
- sql/tekniset_tiedot_view.sql

## Testisivu

http://dataoksi.fi/lab/trafi/tekniset-tiedot

# Lisenssi

Sovellus: MIT

TraFin data: http://www.trafi.fi/tietopalvelut/avoin_data/avoimen_datan_lisenssi

# Tekijä

Marko Wallin <mtw@iki.fi>
