function decodeURIComponentSafe(str) {
	try {
		return decodeURIComponent(str.replace(/\+/g, ' '));
	} catch (e) {
		return str;
	}
}

function popup(message){

	const la_popup = document.getElementById('titleFooter')
	la_popup.innerHTML = decodeURIComponentSafe(message)

	setTimeout(()=>{
		la_popup.classList.add('active')	
	}, 200)

	setTimeout(()=>{
		la_popup.classList.remove('active')	
	}, 5000)
	
}


function alertDebug(message){
	console.log(message)
	alert(message)
}

