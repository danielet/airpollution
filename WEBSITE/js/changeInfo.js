
function member_save()
{
    // 6.form 을 f 에 지정
    var f = document.join;


    if(f.pwd.value == ""){
        alert("Please Insert PW");
        
        return false;
    }
    
    if(f.school.value == ""){
        alert("Please Insert School Name");
        
        return false;
    }
    
    if(f.tel.value == ""){
        alert("Please Insert Tel Num");
        
        return false;
    }

    if(f.pwd.value != f.pwd2.value){
        // 9.비밀번호와 확인이 서로 다르면 경고창으로 메세지 출력 후 함수 종료
        alert("Please Confirm PW");
        
        return false;
    }

    // 10.검사가 성공이면 form 을 submit 한다
    f.submit();

}