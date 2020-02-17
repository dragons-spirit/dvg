var zauber = document.getElementById('divzauber');
zauber.addEventListener('mousemove', drag);
zauber.addEventListener('mouseup', dragstop);

var zauber_icon = null;
var dragx = 0;
var dragy = 0;
var posx = 0;
var posy = 0;

var seite = 0;
var counter = 0;
var counter_0 = 0;
var counter_1 = 0;
var kt_div;
var kt_divs = [[]];
var zindex_count = 1;


function dragstart(zauberdiv){
	zauber_icon = zauberdiv;
	zauber_icon.style.zIndex = zindex_count;
	zindex_count++;
	dragx = posx - zauber_icon.offsetLeft;
	dragy = posy - zauber_icon.offsetTop;
}


function dragstop(){
	var	kt_id_ziel = check_div_auf_div(zauber_icon.offsetLeft, zauber_icon.offsetTop, kt_divs);
	var kt_id = zauber_icon.getAttribute('kt_id');
	var zauber_id = zauber_icon.getAttribute('zauber_id');
	zauber_icon = null;
	if (kt_id_ziel > 0){
		zaubern(kt_id, kt_id_ziel, zauber_id);
	}
}


function drag(ereignis){
	posx = document.all ? window.event.clientX : ereignis.pageX;
	posy = document.all ? window.event.clientY : ereignis.pageY;
	
	if(zauber_icon != null)	{
		zauber_icon.style.left = (posx - dragx) + "px";
		zauber_icon.style.top = (posy - dragy) + "px";
	}
}


while ((kt_div = document.getElementById('kt_div_'+seite+'_'+counter_0)) != null){
	var xy = getRange(kt_div.id, 'kampf_arena');
	kt_divs[counter] = [seite, counter_0, kt_div, xy[0], xy[1], xy[2], xy[3]];
	counter++;
	counter_0++;
}
seite++;
while ((kt_div = document.getElementById('kt_div_'+seite+'_'+counter_1)) != null){
	var xy = getRange(kt_div.id, 'kampf_arena');
	kt_divs[counter] = [seite, counter_1, kt_div, xy[0], xy[1], xy[2], xy[3]];
	counter++;
	counter_1++;
}
	
function check_div_auf_div(div_x, div_y, ziel_divs){
	var div_treffer=0, i;
	for (i=0; i < ziel_divs.length; i++){
		if (div_x >= ziel_divs[i][3] && div_x <= ziel_divs[i][4] && div_y >= ziel_divs[i][5] && div_y <= ziel_divs[i][6]){
			div_treffer = ziel_divs[i][2].getAttribute('kt_id');
		}
	}
	return div_treffer;
}
	
	
	
	
	