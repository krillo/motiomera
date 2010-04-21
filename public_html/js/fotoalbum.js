var id = 0;


function fotoalbumbildTaBort() {
	// ta bort fil ifrån selectboxen
	vald_fil = 0;
	selectbox = document.getElementById('valda_filer');
	for(i=selectbox.options.length-1;i>=0;i--)
	{
		if(selectbox.options[i].selected)
		{
			vald_fil = selectbox.options[i].value;
			selectbox.remove(i);
		}
	}

	if (vald_fil > 0)
	{
		// ta bort innehållet i div'en med file-upload-fältet
		document.getElementById('fil' + vald_fil).innerHTML = '';
	}
}


function fotoalbumbildFlyttaFil() {
	// räkna ut filnamnet (funkar det här på windows? eller behöver vi köra med \ ?)
	filnamn = document.getElementById('image' + id).value;
	arr = filnamn.split('/');
	if (arr.length == 1) {
		// windows platform
		arr = filnamn.split('\\');
	}
	filnamn = arr[arr.length-1];

	// göm nuvarande filväljare
	document.getElementById('filvaljare' + id).style.display = 'none';	

	// lägg till ny filväljare
	id++;
	table = document.getElementById('filer').getElementsByTagName('TBODY')[0];
	tr = document.createElement('TR');
	tr.style.position = 'absolute';
	tr.style.display = 'block';
	tr.id = 'filvaljare' + id;

	td1 = document.createElement('TH');
	td1.style.width = '120px';
	td1.innerHTML = 'Bild';

	td2 = document.createElement('TD');
	td2.style.width = '150px';
	td2.innerHTML = '<div id="fil' + id + '"><input type="file" name="image' + id + '" id="image' + id + '" class="mmFileUpload" onChange="fotoalbumbildFlyttaFil('+ id + ')" /></div>';

	tr.appendChild(td1);
	tr.appendChild(td2);
	table.appendChild(tr);

	// lägg till till selectbox'en med filnamn
	var option = document.createElement("OPTION");
	option.text = filnamn;
	option.value = (id - 1);
	document.getElementById('valda_filer').options.add(option);
}

function fotoalbumRensaUpp() {
	// plocka bort alla filväljare som inte har någon fil satt/vald
	for(x=0;x<(id + 1);x++) {
		if (document.getElementById('image' + x).value == '') {
			document.getElementById('fil' + x).removeChild(document.getElementById('image' + x));
		}
	}
}
