
// input type="text"의 필드 값 유효성을 체크 함
function chkInputTypeText(selector, regex, errorMsg, isFocus) {
    let ele = document.querySelector(selector);
    let value = ele.value;
    if(regex.test(value)) {
        alert(errorMsg);
        isFocus && ele.focus();
        return false;
    }
    return true;
}
// 폼 체크 도우미
function hoyaaCheckForm(options) {
    for(let i=0; i<options.length; i++) {
        // 항목 체크
        if(options[i].type == 'text') {
            if(!chkInputTypeText(options[i].selector, options[i].regex, options[i].errorMsg, options[i].isFocus)) {
                return false;
            }
        }
    }
    return true;
}