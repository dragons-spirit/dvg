SELECT zauber.id AS "ID"
	,zauber.titel AS "Name"
	,zauberart.titel AS "Art"
	,zauber.verbrauch AS "ZP-Verbrauch"
	,GROUP_CONCAT(stufe.wert ORDER BY stufe.wert DESC SEPARATOR '-') AS "Zauberstufe"
	,CASE WHEN ze.art = 'angriff' THEN 'Gegner' ELSE 'Verbündeter' END AS "Ziel"
	,ze.attribut AS "Attribut"
	,ze.wert AS "Wertänderung"
	,ze.runden AS "Anzahl Runden"
	,CASE WHEN ze.jede_runde = 0 THEN 'ja' ELSE 'nein' END AS "Wird mit Effektende zurückgesetzt"
FROM zauber
	LEFT JOIN zauber_effekt ze ON ze.zauber_id = zauber.id
	LEFT JOIN (
		SELECT id, feuer AS wert FROM zauber WHERE feuer > 0
		UNION
		SELECT id, erde AS wert FROM zauber WHERE erde > 0
		UNION
		SELECT id, luft AS wert FROM zauber WHERE luft > 0
		UNION
		SELECT id, wasser AS wert FROM zauber WHERE wasser > 0
		ORDER BY id, wert
		) stufe ON stufe.id = zauber.id
	LEFT JOIN zauberart ON zauberart.id = zauber.zauberart_id
GROUP BY 1,2,3,4,6,7,8,9,10
ORDER BY zauberart.titel, zauber.titel, ze.id