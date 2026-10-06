// alert('Hello Test');

window.onload = function ()
{
	
	/*
	*******************************************************
	***** code pour test CurrentPage et Traduduction ******
	* *****************************************************
	 */
	//if(decodeURI("%?lang=fr&")) {
	//if (window.location.href.indexOf("?lang=fr") > -1 )
	if(window.location.toString().includes("?lang=fr"))
	{
	//	document.getElementById("menu2").style.backgroundColor = "red";
	//	document.getElement("a").style.color = "blue";
		//document.getElementById("menu").style.fontSize = "2em";
		//document.getElementById("testListeLocation").innerHTML = "Hello World!!!";
	//	document.getElementById("testListeLocation").innerText = "Location Test";
	}
	//document.getElementById("menu2").style.backgroundColor = "lightblue";
	//document.getElementById("planOccupationTest").style.color = "red";
	//document.getElementById("planOccupationTest").style.color = "green";
	//document.getElementById("planOccupationTest").style.textTransform = "autocapitalize";
	
	//document.getElementById("testListeLocation").style.textTransform::first-letter = "capitalize";
	//document.getElementById("testListeLocation").style.color = "green";
	
	/*
	*******************************************************
	***** code pour cacher planning logement et bus ******
	* *****************************************************
	 */
	
	if(window.location.toString().includes("planningLogement"))
	{
		
		//document.getElementById("menu").style.backgroundColor = "lightgreen";
		
		/*
		function toggle_by_class(cls, on) {
			var lst = document.getElementsByClassName(cls);
			for(var i = 0; i < lst.length; ++i) {
				lst[i].style.display = on ? '' : 'none';
			}
		}
		*/
	}

	
	/*
	* faire un code qui :
	*   détecte la valeur : ?lang=de
	*   désactive ensuite
	*
	* source :
	*   https://www.delftstack.com/howto/javascript/javascript-if-url-contains-a-string/#:~:text=includes()%20.-,Use%20indexOf()%20to%20Check%20if%20URL%20Contains%20a%20String,should%20be%20your%20search%20string.
	*   https://www.w3schools.com/js/js_htmldom_methods.asp
	*
	* */
	
	
	
}

//document.getElementById("menu2").style.backgroundColor = "blue";
// document.getElementById("menu").style.fontSize = "2em";
// document.getElement("testListeLocation").innerHTML = "Hello World!";

