function req(page, obj, call, args){
	var meth = args && args.meth || "GET";
	var data = args && args.data || null;
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
			var fr = els[i].getElementsByTagName('form')[0];
			id = fr.id;
			e = [];
			var inps = fr.getElementsByTagName('input');
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
	window.addEventListener('dragover', prevent_default, false)
	window.addEventListener('drop', prevent_default, false)
})
function enableUpload(div){
	var c = div.getElementsByClassName('a_ims_cont')[0];
	var z = div.getElementsByClassName('img_uploader')[0];
	console.log(c, z)
	if(!c || !z)
		return;
	z.ondrop = function(e){
		prevent_default(e);

		console.log("drop", arguments)
	}
	z.ondragover = function(e){
		prevent_default(e);

		c.classList.add('img_drop_hover');
	}
	z.ondragleave = z.ondragend = function(e){
		prevent_default(e);
		
		c.classList.remove('img_drop_hover');
		console.log('DDD')
	}
}

/* append adv actions */
window.addEventListener('load', function(){
//	setIdClickAction('wind_append', hide_append);
//	setIdClickAction('wind_append_cont', prevent_click);
//	setIdClickAction('append_send', append_send);
//	setIdClickAction('append_cancel', hide_append);
	setIdClickAction('append_show_button', show_append_add);
})
function show_append_add(){
	req('adv_template', {}, function(d){
		var l = document.getElementById('a_list');
		var t = document.createElement('div');
		l.insertBefore(t, l.firstChild);
		t.outerHTML = d;

		t = l.getElementsByClassName('a_item_template')[0];
		enableUpload(t);
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
function advSaveEdition(div){
	var data = pollAdvData(div);
	if(data === false)
		return;

	var id = div.getElementsByClassName('append_inp_id')[0].value;
	var act = div.getElementsByClassName('append_inp_act')[0].value;

	console.log(div, id, act, data)
	req("adv_manage", {id: id, act: act, data: {props: data}}, function(ans){
	//	console.log("we did it! ", ans)
		var j = JSON.parse(ans);
		if(j.status == 'OK'){
			var par = closestParentClass(div, 'a_item_container');
			par.innerHTML = j.html_adv;

			var ndiv = par.getElementsByClassName('a_item')[0];
		//	console.log("FULL->", par, ndiv)
			setAdvViewFull(ndiv);
		}
	});
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
/*
function hide_append(){
	var q = document.getElementById('wind_append');
	q.classList.remove("visible");
}
function show_append_add(){
	var q = document.getElementById('wind_append');
	q.classList.add("visible");
	document.getElementById('append_inp_id').value = '';
	document.getElementById('append_inp_act').value = 'add';
}
function show_append_edit(id){
	document.getElementById('wind_append').classList.add('visible');
	document.getElementById('append_inp_id').value = id;
	document.getElementById('append_inp_act').value = 'edit';
}
function append_send(){
	var cont = document.getElementsByClassName("widget_append")[0];
	var data = inp_poll(cont);
	if(data === false)
		return;
	var id = document.getElementById('append_inp_id').value;
	var act = document.getElementById('append_inp_act').value;
	console.log("data", data)
	req("adv_manage", {id: id, act: act, data: {props: data}}, function(ans){
		console.log("we did it! ", ans)
	});
}
function inp_poll(div){
	var obj = {};

	var els = div.getElementsByClassName("bl_inp");
	for(var i = 0; i < els.length; i++){
		var e;
		if(els[i].classList.contains('bl_inp_select')){
			e = els[i].getElementsByTagName('select')[0];
		}else if(els[i].classList.contains('bl_inp_integer')){
			e = els[i].getElementsByTagName('input')[0];
		}else if(els[i].classList.contains('bl_inp_bigtext')){
			e = els[i].getElementsByTagName('textarea')[0];
		}
		if(e.value == ""){
			e.focus();
			return false;
		}

		obj[e.id] = e.value;
	}

	return obj;
}
*/
