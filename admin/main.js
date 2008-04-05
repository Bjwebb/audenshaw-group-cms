function urlencode(str) {
    str = escape(str);
    str = str.replace('+', '%2B');
    str = str.replace('%20', '+');
    str = str.replace('*', '%2A');
    str = str.replace('/', '%2F');
    str = str.replace('@', '%40');
    return str;
}

function tab(num) {
    ajax("page="+num, 'content', false);
}

function postAbout() {
    ajax("type=about"+
        "&text="+urlencode(document.getElementById('about').value), 'aboutResult', false);
}

tmpName = '';
tmpText = '';
function cancel() {
    if (tmpName != '') {
        document.getElementById(tmpName).innerHTML = tmpText;
        tmpName = '';
    }
}

function editNews(num) {
    cancel();
    tmpName = 'news'+num;
    tmpText = document.getElementById(tmpName).innerHTML;
    ajax("type=newsform&id="+num,tmpName);
}
function postNews(num) {
    if (num == 'add') { cont = 'newslist'; inc=true; }
    else { cont = 'news'+num; inc=false; }
    ajax("type=newspost&id="+num+
        "&text="+urlencode(document.getElementById('text').value)+
        "&title="+urlencode(document.getElementById('title').value), cont, inc);
    if (num=='add') cancel(); else tmpName = '';
}
function delNews(num) {
    cancel();
    ajax("type=newsdel&id="+num, 'news'+num);
}

function editProj(num) {
    cancel();
    tmpName = 'proj'+num;
    tmpText = document.getElementById(tmpName).innerHTML;
    ajax("type=projform&id="+num,tmpName);
}
function postProj(num) {
    if (num == 'add') { cont = 'projlist'; inc=true; }
    else { cont = 'proj'+num; inc=false; }
    ajax("type=projpost&id="+num+
        "&text="+urlencode(document.getElementById('text').value)+
        "&title="+urlencode(document.getElementById('title').value), cont, inc);
    if (num=='add') cancel(); else tmpName = '';
}
function delProj(num) {
    cancel();
    ajax("type=projdel&id="+num, 'proj'+num);
}

function editPage(num) {
    cancel();
    tmpName = 'page'+num;
    tmpText = document.getElementById(tmpName).innerHTML;
    ajax("type=pageform&id="+num,tmpName,false);
}
function postPage(num) {
    if (num == 'add') { cont = 'pagelist'; inc=true; }
    else { cont = 'page'+num; inc=false; }
    ajax("type=pagepost&id="+num+
        "&name="+urlencode(document.getElementById('name').value)+
        "&text="+urlencode(document.getElementById('text').value)+
        "&title="+urlencode(document.getElementById('title').value), cont, inc);
    if (num=='add') cancel(); else tmpName = '';
}
function delPage(num) {
    cancel();
    ajax("type=pagedel&id="+num, 'page'+num);
}

function editMem(num) {
    cancel();
    tmpName = 'mem'+num;
    tmpText = document.getElementById(tmpName).innerHTML;
    ajax("type=memform&id="+num,tmpName);
}
function postMem(num) {
    if (num == 'add') { cont = 'memlist'; inc=true; }
    else { cont = 'mem'+num; inc=false; }
    ajax("type=mempost&id="+num+
        "&fn="+urlencode(document.getElementById('fn').value)+
        "&ln="+urlencode(document.getElementById('ln').value)+
        "&pos="+urlencode(document.getElementById('pos').value), cont, inc);
    if (num=='add') cancel(); else tmpName = '';
}
function delMem(num) {
    cancel();
    ajax("type=memdel&id="+num, 'mem'+num);
}

function editOth(num) {
    cancel();
    tmpName = 'oth'+num;
    tmpText = document.getElementById(tmpName).innerHTML;
    ajax("type=othform&id="+num,tmpName);
}
function postOth(num) {
    if (num == 'social') { cont = 'othsociallist'; inc=true; }
    else if (num == 'contact') { cont = 'othcontactlist'; inc=true; }
    else if (num == 'links') { cont = 'othlinkslist'; inc=true; }
    else { cont = 'oth'+num; inc=false; }
    ajax("type=othpost&id="+num+
        "&text="+urlencode(document.getElementById('text').value)+
        "&link="+urlencode(document.getElementById('link').value), cont, inc);
    if (num=='social'||num=='contact'||num=='links') cancel(); else tmpName = '';
}
function delOth(num) {
    cancel();
    ajax("type=othdel&id="+num, 'oth'+num);
}
function delCom(num) {
    cancel();
    ajax("type=comdel&id="+num, 'com'+num);
}

