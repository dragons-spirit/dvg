SELECT 
	npc.titel, 
	diag_npc_1.inhalt AS npc_text, 
	CONCAT(dialog_spieler.inhalt, IFNULL(CONCAT(' (', dialog_aktion.titel, ')'), '')) AS "spieler_text (aktion)", 
	diag_npc_2.inhalt AS npc_text_next,
	CONCAT(diag_spieler_2.inhalt, IFNULL(CONCAT(' (', diag_aktion_2.titel, ')'), '')) AS "spieler_text_2 (aktion)", 
	diag_npc_3.inhalt AS npc_text_next
FROM npc
	LEFT JOIN dialog_link_npc ON dialog_link_npc.npc_id = npc.id
	LEFT JOIN dialog_npc diag_npc_1 ON diag_npc_1.id = dialog_link_npc.dialog_npc_id
	LEFT JOIN dialog_link ON dialog_link.dialog_npc_id = diag_npc_1.id
	LEFT JOIN dialog_spieler ON dialog_spieler.id = dialog_link.dialog_spieler_id
	LEFT JOIN dialog_aktion ON dialog_aktion.id = dialog_spieler.dialog_aktion_id
	LEFT JOIN dialog_npc diag_npc_2 ON diag_npc_2.id = dialog_link.next_dialog_npc_id
	LEFT JOIN dialog_link diag_link_2 ON diag_link_2.dialog_npc_id = diag_npc_2.id
	LEFT JOIN dialog_spieler diag_spieler_2 ON diag_spieler_2.id = diag_link_2.dialog_spieler_id
	LEFT JOIN dialog_aktion diag_aktion_2 ON diag_aktion_2.id = diag_spieler_2.dialog_aktion_id
	LEFT JOIN dialog_npc diag_npc_3 ON diag_npc_3.id = diag_link_2.next_dialog_npc_id
where npc.id = 1