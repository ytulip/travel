/*

 */
function SimpleUploadimg(el,params){
    this.$el = $(el); //获得元素的jquery对象
    this.config = params;
    (function(a){
        /*临时生产一个input file，挂载在body最后*/
        a.$file = $('<input type="file" style="display: none;"/>');
        $('body').append(a.$file);
        /*给该input file注册change事件*/
        a.$file.change(function(e){
            console.log(e);
            if(this.value){
                var formData = new FormData();
                formData.append("file" , e.currentTarget
                    .files[0]);//获取文件法二
                $.ajax({
                    url:'/savework',
                    data:formData,
                    type:'post',
                    contentType: false,
                    processData: false,
                    dataType:'json',
                    success:function(data){
                        if(data.status){
                            var bg = document.querySelector(a.config.bg);
                            console.log(data.path);
                            console.log(bg);
                            bg.style.backgroundImage = "url("+data.path+")";
                            bg.style.backgroundSize = "cover";
                            bg.setAttribute('img-data',data.path)
                        }
                    }
                });
            }
        });

        a.$el.click(function(){
            a.$file.click();
            // var e = document.createEvent('HTMLEvents');
            // e.initEvent('click', true, true);
            // el.dispatchEvent(e);
        });
    })(this);
}