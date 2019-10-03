SELECT zauber.id AS "ID"
	,zauber.titel AS "Name"
	,zauber.verbrauch AS "ZP-Verbrauch"
	,CASE WHEN ze.art = 'angriff' THEN 'Gegner' ELSE 'Verbündeter' END AS "Ziel"
	,ze.attribut AS "Attribut"
	,ze.wert AS "Wertänderung"
	,ze.runden AS "Anzahl Runden"
	,CASE WHEN ze.jede_runde = 0 THEN 'ja' ELSE 'nein' END AS "Wird mit Effektende zurückgesetzt"
FROM zauber
	LEFT JOIN zauber_effekt ze ON ze.zauber_id = zauber.id
ORDER BY zauber.titel, ze.id