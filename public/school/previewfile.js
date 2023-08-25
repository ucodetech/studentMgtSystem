// parameters : imageholder, input,labelname
function readURL(input, imageholder,labelname,imgclass, imgwidth=false, imgheight=false){
    if(input.files && input.files[0]){
        let reader = new FileReader();
        reader.onload = function(e){
            $(imageholder).html('<label for="'+labelname+'" class="cursor-pointer" title="click to select new photo"><img src="'+e.target.result+'" class="'+imgclass+'" width="'+imgwidth+'" height="'+imgheight+'"></label>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}