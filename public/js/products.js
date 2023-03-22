// jQuery(document).ready(function($){
//     //you can now use $ as your jQuery object here
//   });
 //********** Product  ********************//

 $(document).ready(function(){
        $('#insert_productForm').on('submit',function(e){
          e.preventDefault();
      
          var form = this;
        //   alert('OJJJJOL');
          $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:new FormData(form),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
              $(form).find('span.error-text').text('');
            },
            success:function(data){
              if (data.status == 0 ) {
                
              } else {          
                Swal.fire({
                  title: 'บันทึกข้อมูลสำเร็จ',
                  text: "You Insert data success",
                  icon: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#06D177',
                  // cancelButtonColor: '#d33',
                  confirmButtonText: 'เรียบร้อย'
                }).then((result) => {
                  if (result.isConfirmed) {                  
                    window.location="/supplies/supplies_index"; //
                  }
                })      
              }
            }
          });
        });
      
        $('#update_productForm').on('submit',function(e){
          e.preventDefault();
      
          var form = this;
          // alert('OJJJJOL');
          $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:new FormData(form),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
              $(form).find('span.error-text').text('');
            },
            success:function(data){
              if (data.status == 0 ) {
                
              } else {          
                Swal.fire({
                  title: 'แก้ไขข้อมูลสำเร็จ',
                  text: "You edit data success",
                  icon: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#06D177',
                  // cancelButtonColor: '#d33',
                  confirmButtonText: 'เรียบร้อย'
                }).then((result) => {
                  if (result.isConfirmed) {                  
                    window.location = "/supplies/supplies_index"; // กรณี add page new  
                  }
                })      
              }
            }
          });
        });   
  });
function supplies_destroy(product_id)
    {
      Swal.fire({
      title: 'ต้องการลบใช่ไหม?',
      text: "ข้อมูลนี้จะถูกลบไปเลย !!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ใช่, ลบเดี๋ยวนี้ !',
      cancelButtonText: 'ไม่, ยกเลิก'
      }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
          url:'/supplies/supplies_destroy/'+product_id,
          type:'DELETE',
          data:{
              _token : $("input[name=_token]").val()
          },
          success:function(response)
          {          
              Swal.fire({
                title: 'ลบข้อมูล!',
                text: "You Delet data success",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#06D177',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'เรียบร้อย'
              }).then((result) => {
                if (result.isConfirmed) {                  
                  $("#sid"+product_id).remove();     
                  // window.location.reload(); 
                  window.location = "/supplies/supplies_index"; //     
                }
              }) 
          }
          })        
        }
        })
}

function addproducts(input) {
    var fileInput = document.getElementById('img');
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var reader = new FileReader();    
            reader.onload = function (e) {
                $('#add_upload_preview').attr('src', e.target.result);
            }    
            reader.readAsDataURL(input.files[0]);
        }else{    
            alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
            fileInput.value = '';
            return false;
            }
}
function editproducts(input) {
  var fileInput = document.getElementById('img');
  var url = input.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
          var reader = new FileReader();    
          reader.onload = function (e) {
              $('#edit_upload_preview').attr('src', e.target.result);
          }    
          reader.readAsDataURL(input.files[0]);
      }else{    
          alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
          fileInput.value = '';
          return false;
          }
}

function addunit() {
  var unitnew = document.getElementById("UNIT_INSERT").value;
  // alert(unitnew);
  var _token = $('input[name="_token"]').val();
  $.ajax({
      url: "/article/addunit",
      method: "GET",
      data: {
        unitnew: unitnew,
          _token: _token
      },
      success: function (result) {
          $('.show_unit').html(result);
      }
  })
}

function addunitsub() {
  var unitnew = document.getElementById("UNIT_SUBINSERT").value;
  // alert(unitnew);
  var _token = $('input[name="_token"]').val();
  $.ajax({
      url: "/article/addunit",
      method: "GET",
      data: {
        unitnew: unitnew,
          _token: _token
      },
      success: function (result) {
          $('.show_unitsub').html(result);
      }
  })
}