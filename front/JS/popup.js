function popup(message){

	const la_popup = document.getElementById('titleFooter')
	//console.log(la_popup)
	//console.log(message)
	la_popup.innerHTML = message

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

