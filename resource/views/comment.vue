<div>
    <div class="essay-icon">
        <!--<a class="m-l-icon"><img src="/images/share.png"/><span>分享</span></a>-->
    </div>

    <div class="barrier-line"></div>
    <div class="essay-comment">
        <textarea v-bind:placeholder="placeholder" v-model="comment"></textarea>
        <div class="comment-group m-t-2"><input placeholder="昵称" v-model="nickname"/><input placeholder="邮件" v-model="email"/></div>
        <div class="comment-group email-add"><span>邮箱地址不会公开</span></div>
        <button v-on:click="publish">发表</button>
    </div>
    <div class="comment-list">
        <div class="comment-list-item" v-for="comment1 in commentlist">
            <div class="comment-item">
                <div class="comment-item-block header-img">
                    <img v-bind:src="comment1.avatar"/>
                </div>
                <div class="comment-item-block item-content">
                    <p class="comment-name-p">{{ comment1.nickname }}：</p>
                    <p class="comment-content-p">{{ comment1.comment_context }}</p>
                    <p class="comment-add-p"><span>{{comment1.created_at}}</span> <a v-on:click="replay" v-bind:data-replay="comment1.replay_info"><span><img src="/public/images/comments.svg"/></span><span class="replay-span">回复</span></a></p>


                </div>
            </div>
            <div class="comment-list-follow" v-for="comment2 in comment1.child">
                <div class="comment-item">
                    <div class="comment-item-block header-img">
                        <img v-bind:src="comment2.avatar"/>
                    </div>
                    <div class="comment-item-block item-content">
                        <p class="comment-name-p">{{ comment2.nickname }}：</p>
                        <p class="comment-content-p">{{ comment2.comment_context }}</p>
                        <p class="comment-add-p"><span>{{comment2.created_at}}</span><a v-on:click="replay" v-bind:data-replay="comment2.replay_info"><span><img src="/public/images/comments.svg"/></span><span class="replay-span">回复</span></a></p>


                    </div>
                </div>
            </div>
        </div>
        <!--<div class="comment-list-item">-->
            <!--<div class="comment-item">-->
                <!--<div class="comment-item-block header-img">-->
                    <!--<img src="//pic.zhuyan.me/1.jpg"/>-->
                <!--</div>-->
                <!--<div class="comment-item-block item-content">-->
                    <!--<p class="comment-name-p">莫小轩：</p>-->
                    <!--<p class="comment-content-p">年半前因为一些原因来到这里上班，即便是每天上班要辗转车我也觉得无所谓。年半前因为一些原因来到这里上班.</p>-->
                    <!--<p class="comment-add-p"><span>两小时前</span> <a><span><img src="/public/images/comments.svg"/></span><span class="replay-span">回复</span></a></p>-->


                <!--</div>-->
            <!--</div>-->
            <!--<div class="comment-list-follow">-->
                <!--<div class="comment-item">-->
                    <!--<div class="comment-item-block header-img">-->
                        <!--<img src="//pic.zhuyan.me/1.jpg"/>-->
                    <!--</div>-->
                    <!--<div class="comment-item-block item-content">-->
                        <!--<p class="comment-name-p">莫小轩：</p>-->
                        <!--<p class="comment-content-p">年半前因为一些原因来到这里上班，即便是每天上班要辗转车我也觉得无所谓。年半前因为一些原因来到这里上班.</p>-->
                        <!--<p class="comment-add-p"><span>两小时前</span> <a><span><img src="/public/images/comments.svg"/></span><span class="replay-span">回复</span></a></p>-->


                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>
    <div  v-if="mask" style="position:fixed;top:0;right:0;bottom:0;left:0;z-index:99;"><div class="cell" style="width:100%;height:100%;display:table"><div class="card" style="display:table-cell;text-align:center;vertical-align:middle">
        <span style="display: inline-block;padding: 7px 14px;background-color: #000000;opacity: .4;color:#ffffdd">发布成功</span>
    </div></div></div>
</div>