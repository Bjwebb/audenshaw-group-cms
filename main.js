function content(num) {
    return document.getElementById('project'+num).innerHTML;
}
function shrink(num) {
    document.getElementById('project'+num).innerHTML = '';
    document.getElementById('link'+num).href = 'javascript:expand('+num+')';
}
function expand(num) {
    document.getElementById('project'+num).innerHTML = projects[num];
    document.getElementById('link'+num).href = 'javascript:shrink('+num+')';
}
function expandAll(num) {
    for (i=1; i<(num+1); i++) {
        expand(i);
    }
}
function shrinkAll(num) {
    for (i=1; i<(num+1); i++) {
        shrink(i);
    }
}
function news_content(num) {
    return document.getElementById('story'+num).innerHTML;
}
function news_shrink(num) {
    document.getElementById('story'+num).innerHTML = '';
    document.getElementById('headline'+num).href = 'javascript:news_expand('+num+')';
}
function news_expand(num) {
    document.getElementById('story'+num).innerHTML = stories[num];
    document.getElementById('headline'+num).href = 'javascript:news_shrink('+num+')';
}
function news_expandAll(num) {
    for (i=1; i<(num+1); i++) {
        news_expand(i);
    }
}
function news_shrinkAll(num) {
    for (i=1; i<(num+1); i++) {
        news_shrink(i);
    }
}
projects = new Array();
function init(num) {
    for (i=1; i<(num+1); i++) {
        projects[i] = content(i);
        shrink(i);
    }
}
stories = new Array();
function news_init(num) {
    for (i=1; i<(num+1); i++) {
        stories[i] = news_content(i);
        news_shrink(i);
    }
}

function nowt() {
}
function picCreate() {
    output = '';
    for (i=start; i<(start+width); i++) {
        if (i >= 0 && i < pics.length) {
            output += "<a href=\"javascript: nowt()\" onclick=\"javascript: picPop('"+pic_titles[i]+"', '"+pics[i]+"', event)\">";
            output += "<img style=\"\" src=\"http://two.xthost.info/aft/thumb/"+pics[i]+"\" alt=\"Pic\" />";
            output += "</a>";
        }
    }
    return output;
}
function picPop(title, url, e) {
//     var posx = 0;
//     var posy = 0;
//     if (!e) var e = window.event;
//     if (e.pageX || e.pageY)         {
//         posx = e.pageX;
//         posy = e.pageY;
//     }
//     else if (e.clientX || e.clientY)        {
//         posx = e.clientX + document.body.scrollLeft
//                 + document.documentElement.scrollLeft;
//         posy = e.clientY + document.body.scrollTop
//                 + document.documentElement.scrollTop;
//     }
      
    document.getElementById('pic_pop').style.left = document.body.scrollLeft + document.documentElement.scrollLeft+'px';
    document.getElementById('pic_pop').style.top = document.body.scrollTop + document.documentElement.scrollTop+'px';
    document.getElementById('pic_pop').style.maxHeight = window.innerHeight+'px';
    document.getElementById('pic_pop').style.maxWidth = window.innerWidth+'0px';
    document.getElementById('pic_pic').innerHTML = 
    '<a href="javascript: nowt()" onclick="javascript:picClose()">'+
    '<img alt="Picture" src="http://two.xthost.info/aft/img/'+url+'" /></a>';
    document.getElementById('pic_title').innerHTML = title;
    document.getElementById('pic_pop').style.visibility = 'visible';
}

window.onscroll = function() {
    document.getElementById('pic_pop').style.left = document.body.scrollLeft + document.documentElement.scrollLeft+'px';
    document.getElementById('pic_pop').style.top = document.body.scrollTop + document.documentElement.scrollTop+'px';
}

function picClose() {
    document.getElementById('pic_pop').style.visibility = 'hidden';
    document.getElementById('pic_pic').innerHTML = '';
}
function picLeft() {
    if (start > 0) {
        start -= width;
        document.getElementById('pic_container').innerHTML = picCreate();
    }
}
function picRight() {
    if ((start+width) < pics.length) {
        start += width;
        document.getElementById('pic_container').innerHTML = picCreate();
    }
}

function doSomething(e) {
}
