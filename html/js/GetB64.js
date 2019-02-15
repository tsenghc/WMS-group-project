   $("#uploadImage").change(function(){
      readImage( this );
    });
 
    function readImage(input) {
      if ( input.files && input.files[0] ) {
        var FR= new FileReader();
        FR.onload = function(e) {
          //e.target.result = base64 format picture
          $('#img').attr( "src", e.target.result );
        };       
        FR.readAsDataURL( input.files[0] );
      }
    }