/*function hideAll(num) {
    for (i=1; i<(num+1); i++) {
        document.getElementById('con'+i).style.visibility = 'hidden';
    }
}
function tab(num) {
    cancel();
    hideAll(5);
    document.getElementById('con'+num).style.visibility = 'visible';
}*/
function tab(num) {
    ajax("page="+num, 'content', false);
}

function postAbout() {
    ajax("type=about"+
        "&text="+document.getElementById('about').value, 'aboutResult', false);
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
        "&text="+document.getElementById('text').value+
        "&title="+document.getElementById('title').value, cont, inc);
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
        "&text="+document.getElementById('text').value+
        "&title="+document.getElementById('title').value, cont, inc);
    if (num=='add') cancel(); else tmpName = '';
}
function delProj(num) {
    cancel();
    ajax("type=projdel&id="+num, 'proj'+num);
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
        "&fn="+document.getElementById('fn').value+
        "&ln="+document.getElementById('ln').value+
        "&pos="+document.getElementById('pos').value, cont, inc);
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
        "&text="+document.getElementById('text').value+
        "&link="+document.getElementById('link').value, cont, inc);
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

function void() {

}
