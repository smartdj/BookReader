/**
 * Created by Arthur on 2016/10/20.
 */
// for Help
/*var g_arrMouseState=new Array();
 var g_arrHelpDiv;

 var g_mX;
 var g_mY;

 $(document).mousemove(function(e){
 g_mX = e.pageX;
 g_mY = e.pageY;
 });

 $(document).ready(function(){

 $(".img_help").mouseenter(function(){
 var tar = parseInt(this.id,10);
 g_arrMouseState[tar] = 1;
 setTimeout(function(){ ShowHelp(this,tar,g_arrHelpDiv[tar]); }, 300);
 });

 $(".img_help").mouseleave(function(){
 var tar = parseInt(this.id,10);
 g_arrMouseState[tar] = 0;
 HideHelp(this,-1,g_arrHelpDiv[tar]);
 });

 });

 function ShowHelp(obj, tar, dv)
 {
 if(g_arrMouseState[tar]!=1) return ;
 dv.style.visibility="visible";
 dv.style.left = g_mX + "px";
 dv.style.top = g_mY+25 + "px";
 }

 function HideHelp(obj, tar, dv){dv.style.visibility="hidden";}
 function Init(){g_arrHelpDiv=$(".text_help");}*/
// End



var iGoodsItem=0;
var acGoods=new Array();
var aiQuant=new Array();
var afPrice=new Array();

var acCodeA=new Array();
var acCodeB=new Array();
var acCodeC=new Array();
var acAlias=new Array();
var afCustom=new Array();
var afRate=new Array();

var request = false;
try {request = new XMLHttpRequest();}
catch (trymicrosoft) {
    try{request = new ActiveXObject('Msxml2.XMLHTTP');}
    catch (othermicrosoft) {
        try{request = new ActiveXObject('Microsoft.XMLHTTP');}
        catch (failed){request = false;}}}

function FillEmskind()
{
    if(x_nEmsKind<=0) return;
    var str="<select size=1 name='cemskind'><option value='' selected>--请选择--</option>";
    for(i=0;i<x_nEmsKind;i++)
    {
        str+="<option value='";
        str+=x_acEmsKind[i];
        str+="'>";
        str+=x_acEmsKindd[i];
        str+="</option>";

    }

    str+="</select>";
    o = document.getElementById("oemskind");
    if(o == null) return ;
    o.innerHTML=str;

}

function addNewAddress(){
    var bOk=true;
    var strErr="";
    var url ="/cgi-bin/GInfo.dll?ajxEmsSRISet&w=ecotransite&ikind=0";
    if(theForm.creceiver.value=='')
    {strErr+='没有填写收件人姓名\r\n';bOk=false;}
    if(theForm.cpostcode.value=='')
    {strErr+='没有填写收件邮编\r\n';bOk=false;}
    if(theForm.ccity.value=='')
    {strErr+='没有填写收件城市\r\n';bOk=false;}
    if(theForm.ccountry.value=='')
    {strErr+='没有填写收件国家\r\n';bOk=false;}
    if(theForm.caddr.value=='')
    {strErr+='没有填写收件地址\r\n';bOk=false;}
    if(theForm.cphone.value=='')
    {strErr+='没有填写手机号码\r\n';bOk=false;}
    if(!bOk) {alert(strErr);bOk=false; return;}

    url+="&cname="+theForm.creceiver.value;
    url+="&cidcard="+theForm.cby1.value;
    url+="&cunit="+theForm.cunitname.value+Math.round(Math.random()*1000000);
    url+="&caddr="+theForm.caddr.value;
    url+="&cphone="+theForm.cphone.value;
    url+="&cpost="+theForm.cpostcode.value;
    url+="&cemail="+theForm.cremail.value;
    url+="&ccity="+theForm.ccity.value;
    url+="&cprovince="+theForm.cprovince.value;
    url+="&ccountry="+theForm.ccountry.value;

    request.open("GET", url, true);
    request.onreadystatechange = addNewAddressOK;
    request.send(null);
}

function addNewAddressOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            var response = request.responseText.split("|");
            if(response[0]<0) {alert(response[1]);return ;}
            QueryAddrList();
            return ;
        }}}

function delAddress(iid){

    var url ="/cgi-bin/GInfo.dll?ajxEmsSRIDel&w=ecotransite&iid="+iid;
    request.open("GET", url, true);
    request.onreadystatechange = delAddressOK;
    request.send(null);
}
function delAddressOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            var response = request.responseText.split("|");
            if(response[0]<=0) return ;
            QueryAddrList();
            return ;
        }}}

function QueryAddrList(){
    var url = "/cgi-bin/GInfo.dll?ajxEmsSRIList&w=ecotransite&nc=100&ikind=100&ntype=1";
    request.open("GET", url, true);
    request.onreadystatechange = QueryAddrListOK;
    request.setRequestHeader("If-Modified-Since","0");
    request.send(null);}

function QueryAddrListOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            eval("var res = " +request.responseText);
            if (res.ReturnValue!=100) { addrList.innerHTML="未检索到结果。"; return; };
            var str='<table width="100%" border="0" cellspacing="0" cellpadding="2">';
            for(var i=0;i<res.iTotalRec;i++)
            {
                str+='<tr><td width="4%"><input type="radio" name="btnAddr" onclick="javascript:QueryEmsSRIGet('+res.RecList[i].iID;
                str+=')"></td><td width="80%" align="left">（';
                str+=res.RecList[i].cName+" 收）　"+res.RecList[i].cAddr+"　"+res.RecList[i].cPhone;
                str+='　</td><td align="center">';
                str+='<a href="javascript:delAddress(';

                str+=res.RecList[i].iID;

                str+=')';
                str+='"　class="f12gray">[删除]</a>';
                str+='</td></tr>';
            }
            str+='</table>';
            addrList.innerHTML=str;
            return ;}}}

//---------------------------------
function QueryEmsSRIGet(iid){
    var url = "/cgi-bin/GInfo.dll?ajxEmsSRIGet&w=ecotransite&ikind=0&iid="+iid;
    request.open("GET", url, true);
    request.onreadystatechange = QueryEmsSRIGetOK;
    request.send(null);}

function QueryEmsSRIGetOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            var response = request.responseText.split("|");
            theForm.creceiver.value=response[1].replace(/&#124;/g,"|");
            var str=response[2].replace(/&#124;/g,"|");
            var n=str.length;
            if(n>0) theForm.cunitname.value=str.substring(0,n-6);
            theForm.caddr.value=response[3].replace(/&#124;/g,"|");
            theForm.cphone.value=response[4].replace(/&#124;/g,"|");
            theForm.cpostcode.value=response[8].replace(/&#124;/g,"|");
            theForm.cremail.value=response[10].replace(/&#124;/g,"|");
            theForm.cby1.value=response[11].replace(/&#124;/g,"|");
            theForm.crsms.value=response[9].replace(/&#124;/g,"|");
            theForm.cdes.value=response[5].replace(/&#124;/g,"|");
            theForm.ccity.value=response[7].replace(/&#124;/g,"|");
            theForm.cprovince.value=response[6].replace(/&#124;/g,"|");
            theForm.ccountry.value=response[5].replace(/&#124;/g,"|");
            return ;}}}
//----------------------------------

function GO(o){
    if(event.keyCode==13){ event.keyCode=9;o.focus();}}
var bLast = false;
var strBase='/cgi-bin/GInfo.dll?';
var strVali='&w=ecotransite';

var strPayDir='PP';
var iLanguage=0;
var strSender='';
var strNum='';
var strRNo='';
var iItemType=1;
var iPayWay=0;
var strEmsKind='';
var strDes='';
var iItem=1;
var fWeight=0;
var iLong=1;
var iWidth=1;
var iHeight=1;
var strGoods='';
var strCodea='';
var strCodeb='';
var strCodec='';
var iQuantity=1;
var fPrice=0;
var strMoney='EUR';
var strPack='';
var strTransNote='';
var fDValue=0;
var fIValue=0;
var fGoods=0;
var fGoodsC=0;
var strReceiver='';
var strUnitName='';
var strAddr='';
var strPhone='';
var strPostCode='';
var strCountry='';
var strProvince='';
var strCity='';
var strMemo='';
var strSUnitName='';
var strSAddr='';
var strSCity='';
var strSProvince='';
var strSCountry='';
var strSPostCode='';
var strSPhone='';
var strSEMail='';
var strSSms='';
var strREMail='';
var strRSms='';


function QueryDes(){
    var url = "/cgi-bin/GInfo.dll?ajxEmsQueryDes&ntype=1&cdes="+theForm.cdes.value;
    request.open("GET", url, true);
    request.onreadystatechange = QueryDesOK;
    request.send(null);}

function QueryDesOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            if(request.responseText=="-") return;
            eval("var res = " +request.responseText);
            if (res.iArea<0) return ;
            iLanguage=res.nLan;
            theForm.cdes.value=res.cDes;
            if(res.cPostCode!="") theForm.cpostcode.value=res.cPostCode;
            if(res.cCity!="") theForm.cprovince.value=res.cProvince;
            theForm.ccountry.value=res.cCountry;
            if(res.cCity!="") theForm.ccity.value=res.cCity;
            return ;}}}

function QuerySender(){
    if(theForm.csender.value=='') return;
    var url='/cgi-bin/GInfo.dll?ajxEmsQuerySender'+strVali+'&csnd='+theForm.csender.value;
    request.open('GET', url, true);
    request.onreadystatechange = QuerySenderOK;
    request.send(null);}
function QuerySenderOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            var response = request.responseText.split("|");
            if(parseInt(response[0],10) <= 0) return ;
            theForm.csunitname.value=response[1].replace(/&#124;/g,"|");
            theForm.csaddr.value=response[2].replace(/&#124;/g,"|");
            theForm.cscountry.value=response[3].replace(/&#124;/g,"|");
            theForm.csprovince.value=response[4].replace(/&#124;/g,"|");
            theForm.cscity.value=response[5].replace(/&#124;/g,"|");
            theForm.csphone.value=response[6].replace(/&#124;/g,"|");
            theForm.cspostcode.value=response[7].replace(/&#124;/g,"|");
            theForm.csemail.value=response[8].replace(/&#124;/g,"|");
            theForm.cssms.value=response[9].replace(/&#124;/g,"|");
            theForm.creceiver.focus();
            return ;}}}
function QueryReceiver(){
    if(theForm.creceiver.value=='') return;
    var url='/cgi-bin/GInfo.dll?ajxEmsQueryReceiver'+strVali+'&crev='+theForm.creceiver.value;
    request.open('GET', url, true);
    request.onreadystatechange = QueryReceiverOK;
    request.send(null);}
function QueryReceiverOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            var response = request.responseText.split("|");
            if(parseInt(response[0],10) <= 0) return ;
            var str=response[1].replace(/&#124;/g,"|");
            var n=str.length;
            if(n>0) theForm.cunitname.value=str.substring(0,n-6);
            theForm.caddr.value=response[2].replace(/&#124;/g,"|");
            theForm.ccountry.value=response[3].replace(/&#124;/g,"|");
            theForm.cprovince.value=response[4].replace(/&#124;/g,"|");
            theForm.ccity.value=response[5].replace(/&#124;/g,"|");
            theForm.cphone.value=response[6].replace(/&#124;/g,"|");
            theForm.cpostcode.value=response[7].replace(/&#124;/g,"|");
            theForm.cremail.value=response[8].replace(/&#124;/g,"|");
            theForm.crsms.value=response[9].replace(/&#124;/g,"|");
            theForm.cgoods.focus();
            return ;}}}

function QueryNum(){
    theNum.innerHTML = '<font color=red>正在查询运单...</font>';
    var url = '/cgi-bin/GInfo.dll?ajxEmsQueryNum'+strVali+'&nver=4&cnum='+theForm.cnum.value;
    request.open('GET', url, true);
    request.onreadystatechange = QueryNumOK;
    request.send(null);}

function QueryNumOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            eval("var res = " +request.responseText);
            if(res.ReturnValue < 0){
                theNum.innerHTML=' 新运单！';return ;}
            if(res.ReturnValue == 0){
                theNum.innerHTML=' 新运单！';return ;}
            if(res.ReturnValue == 2){
                theNum.innerHTML=' 运单已处理！';return ;}
//DispSender();
            theNum.innerHTML=' 记录修改！';
            if(res.GoodsList!=null) iGoodsItem=res.GoodsList.length;
            theForm.iid.value = res.iID;
            theForm.iitem.value=res.iItem;
            theForm.iitemtype.value=res.nItemType;
            theForm.ipayway.value=res.siPayWay;
            theForm.iquantity.value=res.iQuantity;
            theForm.ilong.value=res.iLong;
            theForm.iwidth.value=res.iWidth;
            theForm.iheight.value=res.iHeight;
            theForm.fweight.value=res.fWeight;
            theForm.fprice.value=res.fPrice;
            if(iGoodsItem==1)
            {
                theForm.info_g.value=res.cGoods;
                theForm.info_a.value=res.cGoodsA;
                theForm.info_ca.value=res.cGCodeA;
                theForm.info_cb.value=res.cGCodeB;
                theForm.info_cc.value=res.cGCodeC;
                theForm.info_q.value=res.iQuantity;
                theForm.info_p.value=res.fPrice;
            }

            theForm.fgoods.value=res.fGoods;
            theForm.fgoodsc.value=res.fGoodsc;
            theForm.fdvalue.value=res.fDValue;
            theForm.fivalue.value=res.fIValue;
            theForm.caddr.value=res.cRAddr;
            theForm.ccity.value=res.cRCity;
            theForm.ccountry.value=res.cRCountry;
            theForm.cdes.value=res.cDes;
            theForm.cemskind.value=res.cEmsKind;
            theForm.cgoods.value=res.cGoods;
            theForm.cmemo.value=res.cMemo;
            theForm.cpack.value=res.cPacking;
            theForm.cphone.value=res.cRPhone;
            theForm.cpostcode.value=res.cRPostcode;
            theForm.cprovince.value=res.cRProvince;
            theForm.csender.value=res.cSender;
            theForm.ctransnote.value=res.cTransNote;
            theForm.cunitname.value=res.cRUnit;
            theForm.cmoney.value=res.cMoney;
            theForm.creceiver.value=res.cReceiver;
            theForm.csunitname.value=res.cSUnit;
            theForm.csaddr.value=res.cSAddr;
            theForm.cscity.value=res.cSCity;
            theForm.csprovince.value=res.cSProvince;
            theForm.cscountry.value=res.cSCountry;
            theForm.csphone.value=res.cSPhone;

            theForm.cspostcode.value=res.cSPostcode;
            theForm.ilanguage.value=res.nLanguage;
            theForm.cpaydir.value=res.cPayDir;
            theForm.famount.value=res.fAmount;
            theForm.crno.value=res.cRNo;
            theForm.cssms.value=res.cSSms;
            theForm.csemail.value=res.cSEMail;
            theForm.cremail.value=res.cREMail;
            theForm.crsms.value=res.cRSms;
            theForm.cby1.value=res.cBy1;
            theForm.cby2.value=res.cBy2;
            theForm.cby3.value=res.cBy3;
            theForm.cby4.value=res.cBy4;
            theForm.cby5.value=res.cBy5;
            theForm.calias.value=res.cGoodsA;
            theForm.ccodea.value=res.cGCodeA;
            theForm.ccodeb.value=res.cGCodeB;
            theForm.ccodec.value=res.cGCodeC;
            theForm.fcustom.value=res.fGCustom;
            theForm.frate.value=res.fGCRate;
            theForm.faget.value=res.faGet;
            theForm.fasafe.value=res.faSafe;
            theForm.fapack.value=res.faPack;
            theForm.faother.value=res.faOther;
            theForm.facheck.value=res.faCheck;
            theForm.faremote.value=res.faRemote;
            theForm.faby.value=res.faBy;
            QueryPrice();
            for(i=0;i<iGoodsItem;i++)
            {
                acGoods[i]=res.GoodsList[i].cxGoods;
                acAlias[i]=res.GoodsList[i].cxGoodsA;
                aiQuant[i]=res.GoodsList[i].ixQuantity;
                afPrice[i]=res.GoodsList[i].fxPrice;
                acCodeA[i]=res.GoodsList[i].cxGCodeA;
                acCodeB[i]=res.GoodsList[i].cxGCodeB;
                acCodeC[i]=res.GoodsList[i].cxGCodeC;
                afCustom[i]=res.GoodsList[i].fxGCustom;
                afRate[i]=res.GoodsList[i].fxGCRate;
            }
            UpdateGoodsInfo();
            btnDelete.innerHTML="<button type='button' value='删除本条' class='btn btn-primary' onclick='DeleteIt()'>删除本条</button>";
            return ;}}}

function PartClear(bAdd){
    bLast=bAdd;
    btnDelete.innerHTML = '';
    strPayDir=theForm.cpaydir.value;
    iLanguage=theForm.ilanguage.value;
    strSender=theForm.csender.value;
    strNum=theForm.cnum.value;
    strRNo=theForm.crno.value;
    iItemType=theForm.iitemtype.value;
    iPayWay=theForm.ipayway.value;
    strEmsKind=theForm.cemskind.value;
    strDes=theForm.cdes.value;
    iItem=theForm.iitem.value;
    fWeight=theForm.fweight.value;
    iLong=theForm.ilong.value;
    iWidth=theForm.iwidth.value;
    iHeight=theForm.iheight.value;
    strGoods=theForm.cgoods.value;
    strCodea=theForm.ccodea.value;
    strCodeb=theForm.ccodeb.value;
    strCodec=theForm.ccodec.value;
    iQuantity=theForm.iquantity.value;
    fPrice=theForm.fprice.value;
    strMoney=theForm.cmoney.value;
    strPack=theForm.cpack.value;
    strTransNote=theForm.ctransnote.value;
    fDValue=theForm.fdvalue.value;
    fIValue=theForm.fivalue.value;
    fGoods=theForm.fgoods.value;
    fGoodsC=theForm.fgoodsc.value;
    strReceiver=theForm.creceiver.value;
    strUnitName=theForm.cunitname.value;
    strAddr=theForm.caddr.value;
    strPhone=theForm.cphone.value;
    strPostCode=theForm.cpostcode.value;
    strCountry=theForm.ccountry.value;
    strProvince=theForm.cprovince.value;
    strCity=theForm.ccity.value;
    strMemo=theForm.cmemo.value;
    strSEMail=theForm.csemail.value;
    strSSms=theForm.cssms.value;
    strREMail=theForm.cremail.value;
    strRSms=theForm.crsms.value;
    strSUnitName=theForm.csunitname.value;
    strSAddr=theForm.csaddr.value;
    strSPhone=theForm.csphone.value;
    strSPostCode=theForm.cspostcode.value;
    strSCountry=theForm.cscountry.value;
    strSProvince=theForm.csprovince.value;
    strSCity=theForm.cscity.value;
    theForm.ilanguage.value = 0;
    theForm.cpaydir.value ='PP';
    theForm.iid.value = 0;
    theForm.iitem.value=1;
    theForm.iquantity.value=1;
    theForm.ilong.value=1;
    theForm.iwidth.value=1;
    theForm.iheight.value=1;
    theForm.fweight.value=0;
    theForm.famount.value=0;
    theForm.fprice.value='';
    theForm.fgoods.value='';
    theForm.fgoodsc.value='';
    theForm.fdvalue.value=0;
    theForm.fivalue.value=0;
    theForm.fasafe.value=0;
    theForm.caddr.value='';
    theForm.ccity.value='';
    theForm.ccountry.value='中国';
    theForm.cdes.value='中国';
    theForm.cgoods.value='';
    theForm.ccodea.value='';
    theForm.ccodeb.value='';
    theForm.ccodec.value='';
    theForm.cmemo.value='';
    theForm.cpack.value='';
    theForm.cphone.value='';
    theForm.cpostcode.value='';
    theForm.cprovince.value='';
    theForm.ctransnote.value='';
    theForm.cunitname.value='';
    theForm.crno.value='';
    theForm.creceiver.value='';
    theForm.cremail.value='';
    theForm.crsms.value='';
    thePrice.innerHTML = '';
    theNum.innerHTML = '';
    theForm.cby1.value='';
    iGoodsItem=0;
    theGoodsInfo.innerHTML="";
    GoodsItemAdd('',0,0,'','000000','',0,0,0);

}

function FillSender(){
    theForm.csphone.value=csPhone;
    theForm.cspostcode.value=csPostCode;
    theForm.csemail.value=csSEMail;
    theForm.cssms.value=csSSms;
    if(iLanguage < 2){
        theForm.csunitname.value=csUnitName;
        theForm.csaddr.value=csAddr;
        theForm.cscity.value=csCity;
        theForm.cscountry.value=csCountry;
        theForm.csprovince.value=csProvince;
        theForm.csender.value=csUnitName;}else{
        theForm.csunitname.value=csUnitNameE;
        theForm.csaddr.value=csAddrE;
        theForm.cscity.value=csCityE;
        theForm.cscountry.value=csCountryE;
        theForm.csprovince.value=csProvinceE;
        theForm.csender.value=csUnitNameE;
    }}


function pre_submit(){
    var strErr='请更正如下资料后再提交：\r\n';
    var bOk=true;

    var og=document.getElementsByName('info_g');
    var oq=document.getElementsByName('info_q');
    var op=document.getElementsByName('info_p');
    var oa=document.getElementsByName('info_a');
    var oca=document.getElementsByName('info_ca');
    var ocb=document.getElementsByName('info_cb');
    var occ=document.getElementsByName('info_cc');
    var oct=document.getElementsByName('info_ct');
    var ocr=document.getElementsByName('info_cr');


    if(og == null || oq == null ||op == null) return;
    if(!theForm.cbxNotice.checked)
    {
        document.getElementById("notice").style.display="block";
        strErr+='请阅读并同意会员注册协议方可提交订单\r\n';
        bOk=false;
    }
    else
    {document.getElementById("notice").style.display="none";bOk=true;}

    var strGoods=og[0].value;
    var tquantity=0;
    var iweight=parseFloat(oq[0].value)*parseFloat(occ[0].value);
    if(iweight==""||iweight==0) {strErr+='请正确填写物品净重\r\n';bOk=false;}
    if(iGoodsItem==1)
    {
        theForm.cgoods.value=og[0].value;
        theForm.ccodea.value=oca[0].value;
        theForm.ccodec.value=occ[0].value;
        theForm.iquantity.value=oq[0].value;
        theForm.fprice.value=op[0].value;
        if(theForm.iquantity.value==""||theForm.iquantity.value==0) {strErr+='请正确填写物品数量\r\n';bOk=false;}
        if(theForm.fprice.value==""||theForm.fprice.value==0) {strErr+='请正确填写物品价格\r\n';bOk=false;}

    }
    else {

        for(j=1;j<iGoodsItem;j++)
        {
            if(og[j].value!="")
            {
                strGoods+=","+og[j].value;
                if(oq[j].value==0) {strErr+='请正确填写物品数量\r\n';bOk=false;}
                else tquantity+=parseInt(oq[j].value);
                if(op[j].value==0) {strErr+='请正确填写物品价格\r\n';bOk=false;}
                if(occ[j].value==""||parseFloat(occ[j].value)==0) {strErr+='请正确填写物品净重\r\n';bOk=false;}
                else iweight+=parseInt(oq[j].value)*parseFloat(occ[j].value);

            }
        }
        theForm.cgoods.value = strGoods;
        theForm.iquantity.value=tquantity;
    }
    if(theForm.fweight.value<iweight) {strErr+='申报物品净重:'+iweight+'必须<=包裹重量:'+theForm.fweight.value+'\r\n';bOk=false;}
    if(!IsNum(theForm.fweight.value)||theForm.fweight.value==0||theForm.fweight.value=="") {strErr+='请正确填写包裹重量\r\n';bOk=false;}
    QueryPrice();

    if(iGoodsItem>1&&theForm.info_g[1].value=="")
    {
        for(i=1;i<iGoodsItem;i++)
        {
            theForm.info_a[i].value="";
            theForm.info_ca[i].value="000000";
            theForm.info_cc[i].value="0";
            theForm.info_q[i].value=0;
            theForm.info_p[i].value=0;
        }

    }
    if(theForm.cgoods.value.length>254)
    {strErr+='因“物品描述”项字数过多：\r\n1） 请减少邮寄物品。\r\n2） 请简化物品描述。\r\n';bOk=false;}

    if(theForm.cemskind.value=='')
    {strErr+='没有选择网络\r\n';bOk=false;}
    if(theForm.cdes.value.length<1)
    {strErr+='没有填写目的地\r\n';bOk=false;}
    if(theForm.cgoods.value.length<1)
    {strErr+='没有填写物品描述\r\n';bOk=false;}
    if(theForm.caddr.value.length<1)
    {strErr+='没有填写收件人详细地址\r\n';bOk=false;}
    if(theForm.cpostcode.value.length<1)
    {strErr+='请正确填写收件人邮编\r\n';bOk=false;}
    if(theForm.cphone.value.length<1)
    {strErr+='没有填写收件人电话\r\n';bOk=false;}
    if(theForm.creceiver.value.length<1)
    {strErr+='没有填写收件人姓名\r\n';bOk=false;}
    if(theForm.ccountry.value.length<1)
    {strErr+='没有填写收件人国家\r\n';bOk=false;}
    if(theForm.cunitname.value.length<1)
    {strErr+='没有填写收件人公司\r\n';bOk=false;}
    if(theForm.cprovince.value.length<1)
    {strErr+='没有填写收件人省份\r\n';bOk=false;}
    if(theForm.ccity.value.length<1)
    {strErr+='没有填写收件人城市\r\n';bOk=false;}

    if(!bOk) alert(strErr);
    return bOk;}

function LastValue(){
    theNum.innerHTML='';
    thePrice.innerHTML='';
    theForm.iitem.value=iItem;
    theForm.ilanguage.value=iLanguage;
    theForm.iquantity.value=iQuantity;
    theForm.ilong.value=iLong;
    theForm.iwidth.value=iWidth;
    theForm.iheight.value=iHeight;
    theForm.fweight.value=fWeight;
    theForm.fprice.value=fPrice;
    theForm.fgoods.value=fGoods;
    theForm.fgoodsc.value=fGoodsC;
    theForm.fdvalue.value=fDValue;
    theForm.fivalue.value=fIValue;
    theForm.csunitname.value=strSUnitName;
    theForm.csaddr.value=strSAddr;
    theForm.cscity.value=strSCity;
    theForm.cscountry.value=strSCountry;
    theForm.csphone.value=strSPhone;
    theForm.cspostcode.value=strSPostCode;
    theForm.csprovince.value=strSProvince;
    theForm.csemail.value=strSEMail;
    theForm.cssms.value=strSSms;
    theForm.cremail.value=strREMail;
    theForm.crsms.value=strRSms;
    theForm.caddr.value=strAddr;
    theForm.ccity.value=strCity;
    theForm.ccountry.value=strCountry;
    theForm.cdes.value=strDes;
    theForm.cgoods.value=strGoods;
    theForm.ccodea.value=strCodea;
    theForm.cmemo.value=strMemo;
    theForm.cpack.value=strPack;
    theForm.cmoney.value=strMoney;
    theForm.cphone.value=strPhone;
    theForm.cpostcode.value=strPostCode;
    theForm.cprovince.value=strProvince;
    theForm.csender.value=strSender;
    theForm.ctransnote.value=strTransNote;
    theForm.cunitname.value=strUnitName;
}
function DeleteIt(){
    btnDelete.innerHTML = '<font color=red>正在删除...</font>';
    document.all['theNote'].src = strBase+'RecPreInputDel'+strVali+'&iid='+theForm.iid.value;}


function GoodsItemAdd(cg,iq,fp,ca,cca,ccb,ccc,fct,fcr){
    if(iGoodsItem>0)
    {
        var og=document.getElementsByName('info_g');
        var oq=document.getElementsByName('info_q');
        var op=document.getElementsByName('info_p');
        var oa=document.getElementsByName('info_a');
        var oca=document.getElementsByName('info_ca');
        var ocb=document.getElementsByName('info_cb');
        var occ=document.getElementsByName('info_cc');
        var oct=document.getElementsByName('info_ct');
        var ocr=document.getElementsByName('info_cr');
        if(og == null || oq == null ||op == null) return;

        var n=0;
        for(i=0;i<iGoodsItem;i++)
        {
            acGoods[n]=og[i].value;
            aiQuant[n]=oq[i].value;
            afPrice[n]=op[i].value;
            acAlias[n]=oa[i].value;
            acCodeA[n]=oca[i].value;
            acCodeB[n]=ocb[i].value;
            acCodeC[n]=occ[i].value;
            afCustom[n]=oct[i].value;
            afRate[n]=ocr[i].value;
            n++;
        }
    }

    acGoods[iGoodsItem]=cg;
    aiQuant[iGoodsItem]=iq;
    afPrice[iGoodsItem]=fp;
    acAlias[iGoodsItem]=ca;
    acCodeA[iGoodsItem]=cca;
    acCodeB[iGoodsItem]=ccb;
    acCodeC[iGoodsItem]=ccc;
    afCustom[iGoodsItem]=fct;
    afRate[iGoodsItem]=fcr;
    iGoodsItem++;
    UpdateGoodsInfo();
}

function UpdateGoodsInfo(){
    var str='<table border=0 width=100% cellspacing=1>';
    str += '<tr><td  width=315  align=left>物品名称(请用英文字母填写)<span class="fred">*</span></td><td width=130 align=left>物品数量<span class="fred">* </span></td>';
    str += '<td width=130 align=left>单价(欧元)<span class="fred">*</span></td><td width=130 align=left>单件物品净重(KG)<span class="fred">*</span></td><td align=left> </td></tr>';
    for(i=0;i<iGoodsItem;i++){
        str += '<tr><td><input type=text class="input1" name=info_g  value="';
        str += acGoods[i];
        str += '"></td><input type=hidden name=info_a  class="myinput3" value="';
        str += acAlias[i];
        str += '"><input type=hidden name=info_ca  class="input3" value="';
        str += acCodeA[i];
        str += '"><input type=hidden name=info_cb value="';
        str += acCodeB[i];
        str += '"><input type=hidden name=info_ct value=';
        str += afCustom[i];
        str += '><input type=hidden name=info_cr value=';
        str += afRate[i];
        str += '><td align=left><input type=text name=info_q  onblur="checkNum(this);"  class="input3" value=';
        str += aiQuant[i];
        str += '></td><td align=left><input type=text name=info_p  onblur="checkNum(this);"  class="input3" value=';
        str += afPrice[i];
        str += '></td><td align=left><input type=text name=info_cc  onblur="checkNum(this);"  class="input3" value="';
        str += acCodeC[i];
        str += '"><input type=hidden name=xx  class="input3" value="xx"></td><td align=left class=sblue ';
        if(i>0)
        {str += ' onclick=GoodsItemDel(';
            str += i;
            str += ')>删除';
        }
        else  str +='>';
        str +='</td></tr>';}
    str += '</table>';
    theGoodsInfo.innerHTML = str;
    if(iGoodsItem>=15) document.getElementById("btnAdd").style.display="none";
    else document.getElementById("btnAdd").style.display="block";


}

function GoodsItemDel(ipos){
    var og=document.getElementsByName('info_g');
    var oq=document.getElementsByName('info_q');
    var op=document.getElementsByName('info_p');

    var oa=document.getElementsByName('info_a');
    var oca=document.getElementsByName('info_ca');
    var ocb=document.getElementsByName('info_cb');
    var occ=document.getElementsByName('info_cc');
    var oct=document.getElementsByName('info_ct');
    var ocr=document.getElementsByName('info_cr');
    if(og == null || oq == null ||op == null||oca == null) return;
    var n=0;
    for(i=0;i<iGoodsItem;i++){
        if(i==ipos) continue;
        acGoods[n]=og[i].value;
        aiQuant[n]=oq[i].value;
        afPrice[n]=op[i].value;
        acAlias[n]=oa[i].value;
        acCodeA[n]=oca[i].value;
        acCodeB[n]=ocb[i].value;
        acCodeC[n]=occ[i].value;
        afCustom[n]=oct[i].value;
        afRate[n]=ocr[i].value;
        n++;}
    iGoodsItem--;
    UpdateGoodsInfo();}

var bDispSender=false;
function DispSender(){
    var o=document.getElementById('sndTag');
    if(o==null) return;
    if(bDispSender){
        bDispSender=false;
        o.innerHTML="<button type='button' class='btn btn-primary' onclick='DispSender();'>我要填写发货人信息！</button>";
        sndDiv.style.display='none';return;}
    bDispSender=true;
    o.innerHTML="<font color='#008000'>以下发件人信息请根据要求填写：</font>[<span  onclick='DispSender();' style='cursor:hand;color:#0000FF;text-decoration:underline;'>隐藏</span>] <button type='button' value='用我的默认信息填充' class='btn btn-primary' onclick='FillSender();'>用我的默认信息填充</button>";
    sndDiv.style.display='';}

var bPrintTab = false;
var bPrintLab = false;
var bPrintInv = false;

function TryPrint(ret_cnum){

    var	strTabUrl="http://www.ecotransite.com/cgi-bin/GInfo.dll?EmsPrintTab&w=ecotransite&cnum="+ret_cnum;
    var	strLabUrl="http://www.ecotransite.com/cgi-bin/GInfo.dll?EmsPrintLab&w=ecotransite&cnum="+ret_cnum;

    var	strInvUrl="http://www.ecotransite.com/cgi-bin/GInfo.dll?EmsPrintInv&w=ecotransite&cnum="+ret_cnum;

    if(bPrintTab) window.open(strTabUrl);
    if(bPrintLab) window.open(strLabUrl);
    if(bPrintInv) window.open(strInvUrl);


}

function PrintTab(){
    if(!pre_submit()) return;
    bPrintTab = true;
    bPrintLab = false;
    bPrintInv = false;
    document.theForm.submit();
}

function PrintInv(){
    if(!pre_submit()) return;
    bPrintTab = false;
    bPrintLab = false;
    bPrintInv = true;
    document.theForm.submit();

}

function PrintLab(){
    if(!pre_submit()) return;
    bPrintTab = false;
    bPrintLab = true;
    bPrintInv = false;
    document.theForm.submit();

}
function IsMod(){
    Init();
    var o=document.getElementById('cnum');
    if(o==null) return;
    if(o.value=="")
    {
        FillSender();
    }
}

function CalFasafe()
{
    var oo = document.getElementById("fivalue");
    var o = document.getElementById("fasafe");
    var ofamount = document.getElementById("famount");
    if(oo.value==0) {o.value=0;}
    var reg = /^([0-9]|\.)+$/;
    if(!reg.test(oo.value)||oo.value>1000)
    { alert("请输入正确的金额"); oo.value=0;o.value=0;}
    else if(oo.value>0&&oo.value<=300)
    {
        o.value=2;
    }
    else if(oo.value>300&&oo.value<=500)
    {
        o.value=5;
    }
    else if(oo.value>500&&oo.value<=1000)
    {
        o.value=10;
    }
    calTotalFee();

}
var TotalFee=0;
function QueryPrice(){
    thePrice.innerHTML = '<font color=red>正在查询...</font>';
    var i=1;
    var url='http://www.ecotransite.com/cgi-bin/GInfo.dll?ajxEmsQueryPrice'+strVali+'&cdes='+theForm.cdes.value+'&cemskind='+theForm.cemskind.value+'&itype='+i+'&fweight='+theForm.fweight.value+'&il='+theForm.ilong.value+'&iw='+theForm.iwidth.value+'&ih='+theForm.iheight.value;
    request.open('GET', url, false);
    request.onreadystatechange = QueryPriceOK;
    request.send(null);}
function QueryPriceOK(){
    if (request.readyState == 4){
        if (request.status == 200){
            if(request.responseText.charAt(0) == '-'){
                thePrice.innerHTML=' 没有可用报价';TotalFee=0;calTotalFee();return ;}
            thePrice.innerHTML=' <span class="f14red">'+request.responseText+'</span>欧元。';
            TotalFee=parseFloat(request.responseText);
            calTotalFee();
            return ;}}}

function calTotalFee()
{

    var o=document.getElementById("famount");
    if(o==null) return;
    o.value=TotalFee+parseFloat(theForm.faother.value)+parseFloat(theForm.fasafe.value);
}
function HiddenDiv()
{
    var o = document.getElementById("fasafe");
    var oo = document.getElementById("fivalue");
    document.getElementById('divFasafe').style.display='none';
    oo.value=0;o.value=0;
    calTotalFee();
}
function ShowDiv()
{
    document.getElementById('divFasafe').style.display='block';
}
function checkNum(o) {
    var obtnSub=document.getElementById("osubmit");
    obtnSub.disabled=false;
    var obtnAdd=document.getElementById("btnAdd");
    obtnAdd.disabled=false;
    o.className="input3";
    var aa=o.value;
    if(!IsNum(aa)||o.value == ""||o.value ==0) {o.className="err";obtnSub.disabled=true;obtnAdd.disabled=true;return false;}
}
function IsNum(yx){
    var regStr = /^([0-9]|\.)+$/;
    return regStr.test(yx);
}
function xoResponse(i,mess)
{
    PartClear(true);
    var str='您的订单已确认，订单号为"';
    str+=mess;
    str+='"，请前往<a href="http://www.ecotransite.com/cgi-bin/GInfo.dll?RecPreInputList&w=ecotransite" target="main"><订单管理></a>进行支付。';
    strMess.innerHTML=str;
}
function ValiV(o)
{
    var obtnSub=document.getElementById("osubmit");
    obtnSub.disabled=false;
    theVnote.innerHTML="";
    o.className="myinput3";
    theForm.faother.value=0;
    if(o.value>100) {theVnote.innerHTML="应小于100cm";o.className="errV";obtnSub.disabled=true;return false;}
    var l=parseInt(theForm.ilong.value);
    var w=parseInt(theForm.iwidth.value);
    var h=parseInt(theForm.iheight.value);
    var t=l+w+h;
    if(t>150&&t<=200) theForm.faother.value=10;
    if(t>200) 	{theVnote.innerHTML="长宽高之和小于200";obtnSub.disabled=true;return false;}
    else QueryPrice();
}
function ValiW(o)
{
    var aa=o.value;
    o.className="myinput3";
    if(!IsNum(aa)||o.value == ""||o.value ==0) {o.className="errW";return false;}
    else QueryPrice();
}
