

	function changeModuleStatus ( pModule , pStatus ) {
		document.getElementById(pModule).style.display = pStatus;
	}

	function getElement ( ElementID ) {
		return document.getElementById(ElementID);
	}

	//任
	function switchElement ( ElementID , className ) {
		getElement(ElementID)[className] = getElement(ElementID)[className] == "open" ? "close" : "open";
	}

	//任
	function switchContent ( ElementID , className ) {
		getElement(ElementID)[className] = getElement(ElementID)[className] == "unfold" ? "fold" : "unfold";
	}

	//ʾ
	function changeDiv (obj,ElementID ) {
		var cdiv=document.getElementsByTagName('div');
		var ch2=document.getElementsByTagName('h2');
		for(i=0;i<cdiv.length;i++){
			if(cdiv[i].className=='moduleDivContent'&&cdiv[i].id!=ElementID){
				cdiv[i].style.display='none';
				
			}
			if(cdiv[i].className=='moduleDiv'&&cdiv[i].id!=ElementID){
				cdiv[i].className =  "close" ;
			}
		}
		for(i=0;i<ch2.length;i++){
			if(ch2[i].lang=='moduleDiv'){
				var cspan=ch2[i].getElementsByTagName('div');
				if(cspan[0].lang=='moduleDivSpan'&&cspan[0].className=='close'&&cspan[0]!=obj){
					//alert(ch2[i].innerHTML+'\n'+obj.innerHTML);
					cspan[0].className= "open";
				}
			}
		}
		if(getElement(ElementID).style.display == "none"){
			getElement(ElementID).style.display = "block";
			eval("var imgs=getElement('img"+ElementID+"');");
				imgs.src="../public/images/down.png";
			
		}else{
			getElement(ElementID).style.display ="none";
			eval("var imgs=getElement('img"+ElementID+"');");
				imgs.src="../public/images/down_r.png";
		}
		
		obj.className = obj.className == "close" ? "open" : "close";
	}

	//
	function setDivDisplay ( ElementID , pDisplay ) {
		getElement(ElementID).style.display = pDisplay;
	}

	//Class任
