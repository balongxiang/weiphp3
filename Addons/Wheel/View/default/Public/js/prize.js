
 $(function(){ 
 var show_count = 20;   //要显示的条数 
 var count = $("input:text").val();    //递增的开始值，这里是你的ID 
 var fin_count = parseInt(count) + (show_count-1);   //结束递增的条件 
 
 $("#raise").click(function(){
 if(count < fin_count)    //点击时候，如果当前的数字小于递增结束的条件
 {
 $("ul:eq(0)").clone().appendTo("#prize");   //在表格后面添加一行
   //改变添加的行的ID值。
 }
 });
 });
 function deltr(){
 var length=$("ul").length;
 if(length<=2){
 alert("至少保留一行");
 }else{
 $("tr:last").remove();
 }
 }