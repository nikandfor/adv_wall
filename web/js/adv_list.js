function req(page, obj, call, args){
	var meth = args && args.meth || "GET";
	var data = args && args.data || null;
	if(data != null)
		meth = 'POST';
	var req = new XMLHttpRequest();
	var str = JSON.stringify(obj);
//	console.log("str", str, encodeURI(str), encodeURIComponent(str))
	req.open(meth, '/?j=' + page + '&args=' + encodeURIComponent(str), true);

	req.onreadystatechange = function(){
		if(req.readyState != 4)
			return;
		if(req.status == 200){
			call(req.responseText)
		}else{
			console.log(req.statusText)
		}
	}
	if(typeof data == "object"){
		var f = new FormData();
		for(var k in data)
			f.append(k, data[k]);

		data = f;
	}
	req.send(data);
}
function setIdClickAction(id, call){
	var e = document.getElementById(id);
	if(e)
		e.onclick = call;
}
function closestParentClass(el, cl){
	var item = el;
	while(item != null && !item.classList.contains(cl)){
		item = item.parentElement;
	}

	return item;
}
function prevent_click(ev){
	if(!ev)
		ev = window.event;
	ev.cancelBubble = true;
}
function prevent_default(ev){
	ev = ev || event || window.event;
	ev.bubbles = false;
	ev.cancelBubble = true;
	ev.preventDefault();
}

/* adv actions */
window.addEventListener('load', function(q){
	document.addEventListener('click', function(ev){
		var cl = ev.target.classList;
		if(cl.contains('click_ab')){
			var item = closestParentClass(ev.target, 'a_item');
			var id = item.getAttribute('data-itemid');

			console.log('click_ab', id)

			if(cl.contains('click_ab_rm')){
				if(id == '#'){
					item.parentNode.removeChild(item);
				}else{
					req('adv_manage', {id: id, act: 'rm'}, function(d){
						update_by_filters();
					});
				}
			}else if(cl.contains('click_ab_edit')){
				setAdvViewEdit(item);
			}else if(cl.contains('click_ab_save')){
				advSaveEdition(item);
			}else if(cl.contains('click_ab_hide')){
				setAdvViewCompact(item);
			}else if(cl.contains('click_ab_full')){
				setAdvViewFull(item);
			}
		}
	})
})

/* filters poll*/
window.addEventListener('load', function(q){
	setIdClickAction('btn_filts_apply', update_by_filters);
})
function update_by_filters(){
	var obj = filts_poll(document.getElementsByClassName('filters')[0]);
//	console.log("filters:", obj);
	req('adv_list', {'props': obj}, function(d){
		document.getElementById('main_container').innerHTML = d;
	});
}
function filts_poll(div){
	var obj = {};

	var els = div.getElementsByClassName("bl_filter");
	for(var i = 0; i < els.length; i++){
		var id, e;
		if(els[i].classList.contains('bl_filt_select')){
			id = els[i].getAttribute('data-filtid');
			e = [];
			var inps = els[i].getElementsByTagName('input');
			for(var j = 0; j < inps.length; j++){
				if(inps[j].checked){
					e.push(inps[j].value)
				}
			}
		}else if(els[i].classList.contains('bl_filt_range')){
			id = els[i].id;
			e = {min: els[i].getElementsByClassName('range_inp_min')[0].value,
					max: els[i].getElementsByClassName('range_inp_max')[0].value};
		}
		obj[id] = e;
	}

	return obj;
}

/* login actions */
window.addEventListener('load', function(){
	setIdClickAction('wind_login', hide_login_form);
	setIdClickAction('wind_login_cont', prevent_click);
	setIdClickAction('login_cancel', hide_login_form);
//	setIdClickAction('form_login_send', login_send_form);
	document.getElementById('login_form').onsubmit = login_send_form;

	setIdClickAction('login_show_button', show_login_form);
	setIdClickAction('hmenu_login_logout', login_send_logout);
})
function update_hmenu_login(text){
	document.getElementById("hmenu_login_container").innerHTML = text;
	setIdClickAction('login_show_button', show_login_form);
	setIdClickAction('hmenu_login_logout', login_send_logout);
}
function login_send_form(){
	var l = document.getElementById('form_login_log').value;
	var p = document.getElementById('form_login_pass').value;

	req('login', {}, function(d){
		console.log(d)
		var j = JSON.parse(d);
		if(j.status == 'OK'){
			update_hmenu_login(j.html_hmenu)
			hide_login_form();
		}
	}, {meth: 'POST', data: {l: l, p: p}})
}
function login_send_logout(){
	req('logout', {}, function(d){
	//	console.log(d)
		var j = JSON.parse(d);
		update_hmenu_login(j.html_hmenu)
	})
}
function hide_login_form(){
	var q = document.getElementById('wind_login');
	q.classList.remove("visible");
}
function show_login_form(){
	var q = document.getElementById('wind_login');
	q.classList.add("visible");
	document.getElementById('form_login_log').focus();
}

/* upload images */
window.addEventListener('load', function(){
	window.addEventListener('dragover', dragDrop, false)
	window.addEventListener('drop', dragDrop, false)
	window.addEventListener('dragleave', dragDrop, false)

	var ib = document.getElementsByClassName('a_ims_buttons');
	for(var i = 0; i < ib.length; i ++){
		var node = ib[0];

		while(node.hasChildNodes()){
			node.removeChild(node.lastChild);
		}
	}
})
var image_sort_next = 100;
function dragDrop(ev){
	prevent_default(ev);

	var up = closestParentClass(ev.target, 'img_uploader');
	while(up != null){
		var item = closestParentClass(up, 'a_item');
		var id = item.getAttribute('data-itemid');
		if(!item.classList.contains('a_item_edit'))
			break;

		switch(ev.type){
			case "dragover":
				up.classList.add("img_drop_hover");
				break;
			case "drop":
				var fs = ev.dataTransfer.files;
				dropImage(item, fs);
			case "dragleave":
				up.classList.remove("img_drop_hover");
				break;
		}

	//	console.log(id, ev.type, ev)
		break;
	}

	return false;
}
function dropImage(item, fs){
	for(var i = 0; i < fs.length; i++){
		var f = fs[i];
		if(f.type != "image/jpeg" && f.type != "image/png")
			continue;
		var reader = new FileReader();
		reader.onload = function(ev1){
			var img_div = document.createElement('div')
			img_div.innerHTML = "<div class=a_ims_img><img src=" + ev1.target.result + "></div>";
			img_div = img_div.children[0];
			img_div.data_file = f;
			var cont = item.getElementsByClassName('a_ims_cont_pos')[0];
			cont.appendChild(img_div);

		/*	req('img_upload', {fileid: 'f1'}, function(d){
				console.log('file uploaded', d)
				var j = JSON.parse(d);
				if(j.status == 'OK'){
					img_div.data_imid = d.imid;
				}
			}, {meth: "POST", data: {f1: f}});
		*/
		}
		reader.readAsDataURL(f);
	}
}

/* append adv actions */
window.addEventListener('load', function(){
//	setIdClickAction('wind_append', hide_append);
//	setIdClickAction('wind_append_cont', prevent_click);
//	setIdClickAction('append_send', append_send);
//	setIdClickAction('append_cancel', hide_append);
	setIdClickAction('append_show_button', show_append_add);

	document.body.classList.remove('js_unavailable');
})
function show_append_add(){
	req('adv_template', {}, function(d){
		var l = document.getElementById('a_list');
		var t = document.createElement('div');
		l.insertBefore(t, l.firstChild);
		t.outerHTML = d;

	//	t = l.getElementsByClassName('a_item_template')[0];
	//	enableUpload(t);
	});
}
function pollAdvData(div){
	var obj = {};
	var els = div.getElementsByClassName('prop_edit_val');
	for(var i = 0; i < els.length; i++){
		obj[els[i].name] = els[i].value;
	}

	return obj;
}
function pollAdvIms(div){
	var data = [];
	var files = {};
	var els = div.getElementsByClassName('a_ims_img');
	for(var i = 0; i < els.length; i++){
		var e = els[i];
		if(e.data_file){
			var q = 'file' + i;
			data.push({act: 'add', sort: 100 + i, fileid: q});
			files[q] = e.data_file;
		}else if(e.data_imid){
			var o = {act: 'edit', sort: 100 + i, imid: e.data_imid};
			data.push(o);
		}
	}

	return {data: data, files: files};
}
function advSaveEdition(div){
	var data = pollAdvData(div);
	if(data === false)
		return;
	var ims = pollAdvIms(div);

	var id = div.getElementsByClassName('append_inp_id')[0].value;
	var act = div.getElementsByClassName('append_inp_act')[0].value;

	console.log(div, id, act, data)
	req("adv_manage", {id: id, act: act, props: data, ims: ims.data}, function(ans){
	//	console.log("we did it! ", ans)
		try{
			var j = JSON.parse(ans);
			if(j.status == 'OK'){
				var par = closestParentClass(div, 'a_item_container');
				par.innerHTML = j.html_adv;

				var ndiv = par.getElementsByClassName('a_item')[0];
			//	console.log("FULL->", par, ndiv)
				setAdvViewFull(ndiv);
			}else{
				console.log(j);
			}
		}catch(e){
			console.log(ans);
		}
	}, {data: ims.files});
}
function setAdvViewEdit(div){
	div.classList.remove('a_item_compact');
	div.classList.remove('a_item_full');
	div.classList.add('a_item_edit');
}
function setAdvViewFull(div){
	div.classList.remove('a_item_compact');
	div.classList.remove('a_item_edit');
	div.classList.add('a_item_full');
}
function setAdvViewCompact(div){
	div.classList.remove('a_item_full');
	div.classList.remove('a_item_edit');
	div.classList.add('a_item_compact');
}

/* search */
window.addEventListener('load', function(){
	document.getElementsByClassName('a_search_text')[0].onkeyup = search_send;
	document.getElementsByClassName('a_search_send')[0].onclick = search_send;
})
var searchTimer = null;
function search_send(ev){
	if(searchTimer)
		clearTimeout(searchTimer);
	searchTimer = setTimeout(function(){
		var text = ev.target.value;
		document.getElementsByClassName('a_search_send')[0].classList.add('a_search_wait');
		req('adv_search', {q: text}, function(d){
			document.getElementById('main_container').innerHTML = d;
			document.getElementsByClassName('a_search_send')[0].classList.remove('a_search_wait');
		})
	}, 100);
}
