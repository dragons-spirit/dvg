var rahmen = document.getElementById('rahmen');
rahmen.addEventListener('mousemove', pos_mover);
rahmen.addEventListener('mouseover', pos_mover);
rahmen.addEventListener('mouseout', pos_mover);

/*
resize
*/

function pos_mover(ereignis){
	posx = document.all ? window.event.clientX : ereignis.pageX;
	posy = document.all ? window.event.clientY : ereignis.pageY;
	
	var x = document.getElementsByTagName("mover");
	var i;
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	
	var elem = ereignis.target;
	var mover = null, top_mover = null, top_mover_parent = null, grenzen, tagname = "";
	while ((typeof(elem) == "object") && (typeof(elem.tagName) != "undefined")){
		
		tagname = elem.tagName.toUpperCase();
		if (tagname == "TOP_MOVER"){
			top_mover = elem;
			top_mover_parent = top_mover.offsetParent;
			kinder = top_mover.childNodes;
			var k = 0;
			while (mover == null && k < kinder.length){
				if (typeof(kinder[k].tagName) != "undefined" && kinder[k].tagName.toUpperCase() == "MOVER"){
					mover = kinder[k];
				} else {
					k++;
				}
			}
		}
		if (tagname == "BODY" || tagname == "TOP_MOVER"){
			elem = 0;
		}
		if (typeof(elem) == "object")
			if (typeof(elem.parentNode) == "object")
				elem = elem.parentNode;
	}
	if (top_mover_parent != null && mover != null && typeof(top_mover_parent) == "object"){
		var position = top_mover_parent.getBoundingClientRect();
		var links = position.left;
		var rechts = position.left + top_mover_parent.offsetWidth;
		var oben = position.top;
		var unten = position.top + top_mover_parent.offsetHeight;
		if ((links > posx) || (rechts < posx) || (oben > posy) || (unten < posy)){
			mover.style.display = "none";
		} else {
			mover.style.display = "block";
			mover.style.left = posx + 1 + 25 + "px";
			mover.style.top = posy + 1 + 23 + "px";
		}
	}
}


