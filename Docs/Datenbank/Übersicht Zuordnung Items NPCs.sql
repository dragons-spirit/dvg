SELECT items.titel AS "Item",
	npc.titel AS "NPC",
	npc_items.wahrscheinlichkeit "Wkt",
	npc_items.anzahl_min||'-'||npc_items.anzahl_max AS "Anzahl"
FROM items
	LEFT JOIN npc_items ON npc_items.items_id = items.id
	LEFT JOIN npc ON npc.id = npc_items.npc_id
ORDER BY 1,2,3