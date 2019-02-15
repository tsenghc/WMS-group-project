
<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<head>
        <meta charset="utf-8" />

<script>function sendData() {
        $(function () {            
                    $.ajax({
                        url: "https://taki.dog/api/account/login",   //存取Json的網址             
                        type: "POST",
                        cache:false,
                        contentType: 'application/json',
                        dataType: 'json',
                        data:JSON.stringify({userid:"test", password:"fb469d7ef430b0baf0cab6c436e70375"}),
                        success: function (data) {
                            alert("test");
                            alert(data['token']);
                            $.cookie('token', data['token'], { path:'/', expires: 5 });        
                            //document.cookie = "name=test";
                            location.reload();           
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                    });
                });
                alert("flash");
            }
        
            </script>
                        
<script>function showCookie() {
     $(function () {
        alert($.cookie("token"));

     });
}
                            
</script>
 
 
 <script>function show_input_data() {
    $(function () {
        var info = $('#text_info').val();
        var place = $('#text_place').val();
        var quan = $('#text_quan').val();
        alert(info+" "+place+" "+quan);

    });
}                     
</script>
 <script>function readURL(input) {
$(function () {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //$('#falseinput').attr('src', e.target.result);
            //$('#base').val(e.target.result);
            base64_data=(e.target.result);
            
        };
        reader.readAsDataURL(input.files[0]);
    }
    });
}
</script>




<script>function ItemAdd() {
    $(function () {            
                $.ajax({
                    url: "https://taki.dog/api/item/add",              
                    type: "POST",
                    cache:false,
                    contentType: 'application/json',
                    dataType: 'json',
                    data:JSON.stringify(
                        {
                            token:$.cookie("token"),
                            iteminfo:$('#text_info').val(),
                            placeid:$('#text_place').val(),
                            Image:base64_data,
                            quan:$('#text_quan').val()
                        }
                            
                    ),success: function (data) {
                        alert("ya!! success update");
                        alert(data['error']);
                        //alert(data['token']);
                        //$.cookie('token', data['token'], { path:'/', expires: 5 });        
                        //document.cookie = "name=test";
                        //location.reload();           
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            });
            alert("上傳中..");
        }
    
        </script>








<button type="button1" onclick=sendData()>set cookies and reload</button>
<button type="button2" onclick=showCookie()  >test show</button> 
<br>
<div>itemInfo: <input type="text" id="text_info" /></div>
<div>placeId: <input type="text" id="text_place" /></div>
<div>quan: <input type="text" id="text_quan" /></div>
<input id="fileinput" type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" />

<button type="button2" onclick=show_input_data()  > show_input_without_base64</button> 
<button type="button2" onclick=alert(base64_data)  > test show base 64</button> 

<br>
<br>

<button type="update all Data" onclick=ItemAdd()  > all Update</button> 

</head>

</html>

