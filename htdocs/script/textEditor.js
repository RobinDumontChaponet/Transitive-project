function makeWYSIWYG(where){
	var buttonsContainer = document.createElement('div'), timeout;

	buttonsContainer.className='contenteditableButtons';
	buttonsContainer.innerHTML='<input type="image" src="style/admin/images/bold.png" data-tag="bold" /><input type="image" src="style/admin/images/italic.png" data-tag="italic" /><input type="image" src="style/admin/images/underline.png" data-tag="underline" /><input type="image" src="style/admin/images/stroke.png" data-tag="strikeThrough" /><input type="image" src="style/admin/images/link.png" data-tag="createLink" /><input type="image" src="style/admin/images/no_style.png" data-tag="removeFormat" /><input type="image" src="style/admin/images/left_align.png" data-tag="justifyleft" /><input type="image" src="style/admin/images/center_align.png" data-tag="justifycenter" /><input type="image" src="style/admin/images/right_align.png" data-tag="justifyright" />';

	where.parentNode.insertBefore(buttonsContainer, where);

	where.onfocus=function(){clearTimeout(timeout);buttonsContainer.style.display='block';buttonsContainer.style.opacity=1};
	where.onblur=function(){timeout=setTimeout(function(){buttonsContainer.style.opacity=0;buttonsContainer.style.display='none';},150)};

	//Get the format buttons
	var buttons = buttonsContainer.getElementsByTagName('input');
	
	//For each of them...
	for(var i=0, l=buttons.length; i<l; i++){
		//We bind the click event
		buttons[i].addEventListener('mousedown',function(e){
			clearTimeout(timeout);
			var tag = this.getAttribute('data-tag');
			switch(tag){
				case 'createLink':
					var link = prompt('Entrez l\'url :');
					if(link){
						document.execCommand('createLink', false, link);
					}
				break;
				
				case 'insertImage':
					var src = prompt('Entrez l\'adresse de l\'image :');
					if(src){
						document.execCommand('insertImage', false, src);
					}
				break;
				
				case 'heading':
					try{
						document.execCommand(tag, false, this.getAttribute('data-value'));
					}
					catch(e){
						//The browser doesn't support "heading" command, we use an alternative
						document.execCommand('formatBlock', false, '<'+this.getAttribute('data-value')+'>');
					}
				break;
				
				default:
					document.execCommand(tag, false, this.getAttribute('data-value'));
			}
			e.preventDefault();
		});
	}		
};