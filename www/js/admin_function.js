function dialog_box(message){	$('#dialog').dialog('open');	$('#dialog').html(message);		$('#dialog').dialog({		autoOpen: false,		show: "blind",		hide: "explode",		width: 600,		buttons: {			"Ok": function() {				$(this).dialog("close");				return true;				// $.ajax({ 								//һ��Ajax����					// type: "get", 											// url : "admin.php",					//���phpҳ�湵ͨ					// data: 'action=media&do=category_del&type=edit&id='+m_c_id, 	//����php������������ֱ������洫����u��p					// success: function(result){			//�������php�ɹ�						// location.href ="?action=media&do=category";					// }				// });			},			"Cancel": function() {				$(this).dialog("close");				return false;			}		}	});	}