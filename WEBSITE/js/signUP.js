function member_save()
{
    var f = document.join; // f (variable) as form(form name is join) save 

    if(f.email.value == ""){ // input form check         
        return false;
    }
    if(f.name.value == ""){        
        return false;
    }
    
    if(f.school.value == ""){
        return false;
    }
    
    if(f.tel.value == ""){        
        return false;
    }

    if(f.pwd.value == ""){
        return false;
    }

    if(f.pwd.value != f.pwd2.value){    
        return false;
    }

    // 10.검사가 성공이면 form 을 submit 한다
    f.submit();
}

// function oneCheckbox(check){
//         var obj = document.getElementsByName("pollution");
//         for(var i=0; i<obj.length; i++){
//             if(obj[i] != check){
//                 obj[i].checked = false;
//             }
//         }
//     }