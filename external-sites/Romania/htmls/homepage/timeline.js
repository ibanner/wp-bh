function MM_timelinePlay(tmLnName, myID) { //v1.2
  //Copyright 1997 Macromedia, Inc. All rights reserved.
  var i,j,tmLn,props,keyFrm,sprite,numKeyFr,firstKeyFr,propNum,theObj,firstTime=false;
  if (document.MM_Time == null) MM_initTimelines(); //if *very* 1st time
  tmLn = document.MM_Time[tmLnName];
  if (myID == null) { myID = ++tmLn.ID; firstTime=true;}//if new call, incr ID
  if (myID == tmLn.ID) { //if Im newest
    setTimeout('MM_timelinePlay("'+tmLnName+'",'+myID+')',tmLn.delay);
    fNew = ++tmLn.curFrame;
    for (i=0; i<tmLn.length; i++) {
      sprite = tmLn[i];
      if (sprite.charAt(0) == 's') {
        if (sprite.obj) {
          numKeyFr = sprite.keyFrames.length; firstKeyFr = sprite.keyFrames[0];
          if (fNew >= firstKeyFr && fNew <= sprite.keyFrames[numKeyFr-1]) {//in range
            keyFrm=1;
            for (j=0; j<sprite.values.length; j++) {
              props = sprite.values[j]; 
              if (numKeyFr != props.length) {
                if (props.prop2 == null) sprite.obj[props.prop] = props[fNew-firstKeyFr];
                else        sprite.obj[props.prop2][props.prop] = props[fNew-firstKeyFr];
              } else {
                while (keyFrm<numKeyFr && fNew>=sprite.keyFrames[keyFrm]) keyFrm++;
                if (firstTime || fNew==sprite.keyFrames[keyFrm-1]) {
                  if (props.prop2 == null) sprite.obj[props.prop] = props[keyFrm-1];
                  else        sprite.obj[props.prop2][props.prop] = props[keyFrm-1];
        } } } } }
      } else if (sprite.charAt(0)=='b' && fNew == sprite.frame) eval(sprite.value);
      if (fNew > tmLn.lastFrame) tmLn.ID = 0;
  } }
}


function MM_initTimelines() {
    //MM_initTimelines() Copyright 1997 Macromedia, Inc. All rights reserved.
    var ns = navigator.appName == "Netscape";
    document.MM_Time = new Array(1);
    document.MM_Time[0] = new Array(7);
    document.MM_Time["Timeline1"] = document.MM_Time[0];
    document.MM_Time[0].MM_Name = "Timeline1";
    document.MM_Time[0].fps = 12;
    document.MM_Time[0][0] = new String("sprite");
    document.MM_Time[0][0].slot = 1;
    if (ns)
        document.MM_Time[0][0].obj = document["rollover3layer"];
    else
        document.MM_Time[0][0].obj = document.all ? document.all["rollover3layer"] : null;
    document.MM_Time[0][0].keyFrames = new Array(20, 39);
    document.MM_Time[0][0].values = new Array(3);
    document.MM_Time[0][0].values[0] = new Array(-150,-109,-68,-27,15,56,97,138,179,220,262,303,344,385,426,467,509,550,591,632);
    document.MM_Time[0][0].values[0].prop = "left";
    document.MM_Time[0][0].values[1] = new Array(8,8,8,8,8,8,8,8,8,8,7,7,7,7,7,7,7,7,7,7);
    document.MM_Time[0][0].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][0].values[0].prop2 = "style";
        document.MM_Time[0][0].values[1].prop2 = "style";
    }
    document.MM_Time[0][0].values[2] = new Array("inherit","visible");
    document.MM_Time[0][0].values[2].prop = "visibility";
    if (!ns)
        document.MM_Time[0][0].values[2].prop2 = "style";
    document.MM_Time[0][1] = new String("sprite");
    document.MM_Time[0][1].slot = 2;
    if (ns)
        document.MM_Time[0][1].obj = document["rollover2layer"];
    else
        document.MM_Time[0][1].obj = document.all ? document.all["rollover2layer"] : null;
    document.MM_Time[0][1].keyFrames = new Array(20, 39);
    document.MM_Time[0][1].values = new Array(4);
    document.MM_Time[0][1].values[0] = new Array(-600,-542,-484,-426,-368,-310,-252,-194,-136,-78,-20,38,96,154,212,270,328,386,444,502);
    document.MM_Time[0][1].values[0].prop = "left";
    document.MM_Time[0][1].values[1] = new Array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);
    document.MM_Time[0][1].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][1].values[0].prop2 = "style";
        document.MM_Time[0][1].values[1].prop2 = "style";
    }
    document.MM_Time[0][1].values[2] = new Array("inherit","inherit");
    document.MM_Time[0][1].values[2].prop = "visibility";
    if (!ns)
        document.MM_Time[0][1].values[2].prop2 = "style";
    document.MM_Time[0][1].values[3] = new Array(4,3,3,3,3,3,3,3,3,3,2,2,2,2,2,2,2,2,2,2);
    document.MM_Time[0][1].values[3].prop = "width";
    if (!ns)
        document.MM_Time[0][1].values[3].prop2 = "style";
    document.MM_Time[0][2] = new String("sprite");
    document.MM_Time[0][2].slot = 3;
    if (ns)
        document.MM_Time[0][2].obj = document["rollover1layer"];
    else
        document.MM_Time[0][2].obj = document.all ? document.all["rollover1layer"] : null;
    document.MM_Time[0][2].keyFrames = new Array(20, 39);
    document.MM_Time[0][2].values = new Array(3);
    document.MM_Time[0][2].values[0] = new Array(-1000,-928,-855,-783,-711,-639,-566,-494,-422,-350,-277,-205,-133,-61,12,84,156,228,301,373);
    document.MM_Time[0][2].values[0].prop = "left";
    document.MM_Time[0][2].values[1] = new Array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);
    document.MM_Time[0][2].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][2].values[0].prop2 = "style";
        document.MM_Time[0][2].values[1].prop2 = "style";
    }
    document.MM_Time[0][2].values[2] = new Array("inherit","inherit");
    document.MM_Time[0][2].values[2].prop = "visibility";
    if (!ns)
        document.MM_Time[0][2].values[2].prop2 = "style";
    document.MM_Time[0][3] = new String("sprite");
    document.MM_Time[0][3].slot = 5;
    if (ns)
        document.MM_Time[0][3].obj = document["img2layer"];
    else
        document.MM_Time[0][3].obj = document.all ? document.all["img2layer"] : null;
    document.MM_Time[0][3].keyFrames = new Array(1, 20);
    document.MM_Time[0][3].values = new Array(2);
    document.MM_Time[0][3].values[0] = new Array(-300,-277,-254,-230,-207,-184,-161,-138,-114,-91,-68,-45,-21,2,25,48,71,95,118,141);
    document.MM_Time[0][3].values[0].prop = "left";
    document.MM_Time[0][3].values[1] = new Array(46,46,46,46,46,46,46,46,46,46,46,46,46,46,46,46,46,46,46,46);
    document.MM_Time[0][3].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][3].values[0].prop2 = "style";
        document.MM_Time[0][3].values[1].prop2 = "style";
    }
    document.MM_Time[0][4] = new String("sprite");
    document.MM_Time[0][4].slot = 6;
    if (ns)
        document.MM_Time[0][4].obj = document["img1layer"];
    else
        document.MM_Time[0][4].obj = document.all ? document.all["img1layer"] : null;
    document.MM_Time[0][4].keyFrames = new Array(1, 20);
    document.MM_Time[0][4].values = new Array(2);
    document.MM_Time[0][4].values[0] = new Array(23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23);
    document.MM_Time[0][4].values[0].prop = "left";
    document.MM_Time[0][4].values[1] = new Array(600,581,561,542,523,503,484,465,445,426,407,388,368,349,330,310,291,272,252,233);
    document.MM_Time[0][4].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][4].values[0].prop2 = "style";
        document.MM_Time[0][4].values[1].prop2 = "style";
    }
    document.MM_Time[0][5] = new String("sprite");
    document.MM_Time[0][5].slot = 7;
    if (ns)
        document.MM_Time[0][5].obj = document["img3layer"];
    else
        document.MM_Time[0][5].obj = document.all ? document.all["img3layer"] : null;
    document.MM_Time[0][5].keyFrames = new Array(1, 20);
    document.MM_Time[0][5].values = new Array(2);
    document.MM_Time[0][5].values[0] = new Array(800,769,738,708,677,646,615,584,554,523,492,461,431,400,369,338,307,277,246,215);
    document.MM_Time[0][5].values[0].prop = "left";
    document.MM_Time[0][5].values[1] = new Array(239,239,239,239,239,239,239,239,239,239,239,239,239,239,239,239,239,239,239,239);
    document.MM_Time[0][5].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][5].values[0].prop2 = "style";
        document.MM_Time[0][5].values[1].prop2 = "style";
    }
    document.MM_Time[0][6] = new String("sprite");
    document.MM_Time[0][6].slot = 4;
    if (ns)
        document.MM_Time[0][6].obj = document["startlayer"];
    else
        document.MM_Time[0][6].obj = document.all ? document.all["startlayer"] : null;
    document.MM_Time[0][6].keyFrames = new Array(20, 39);
    document.MM_Time[0][6].values = new Array(2);
    document.MM_Time[0][6].values[0] = new Array(1000,976,953,929,906,882,859,835,811,788,764,741,717,693,670,646,623,599,576,552);
    document.MM_Time[0][6].values[0].prop = "left";
    document.MM_Time[0][6].values[1] = new Array(213,213,213,213,213,213,213,213,213,213,213,213,213,213,213,213,213,213,213,213);
    document.MM_Time[0][6].values[1].prop = "top";
    if (!ns) {
        document.MM_Time[0][6].values[0].prop2 = "style";
        document.MM_Time[0][6].values[1].prop2 = "style";
    }
    document.MM_Time[0].lastFrame = 39;
    for (i=0; i<document.MM_Time.length; i++) {
        document.MM_Time[i].ID = null;
        document.MM_Time[i].curFrame = 0;
        document.MM_Time[i].delay = 1000/document.MM_Time[i].fps;
    }
}