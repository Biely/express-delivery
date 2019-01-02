<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>蜜蜂金服工单系统</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css"> -->
        <link rel="stylesheet"  href="{{  asset('homes/css/base.css') }}" />
        <link rel="stylesheet" href="{{  asset('homes/css/skitter.styles.css') }}"  />
        <link rel="stylesheet"  href="{{  asset('homes/css/main.css') }}" />
        <!-- Styles -->
        <style>
            body{
                position: relative;
            }
            .full-height {
                height:60px;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: fixed;
                width: 100%;
                background-color: #fff;
                z-index: 999;
            }
            .top-left {
                position: absolute;
                left: 10px;
                top: 18px;
            }
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }
            .links > p {
                color: #636b6f;
                padding: 0 25px;
                font-size: 26px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-left links">
                <p>蜜蜂快递售后服务中心</p>
            </div>
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">进入系统</a>
                    @else
                        <a href="{{ route('login') }}">登录</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">注册</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        <div class="box_skitter box_skitter_large" style="padding-top: 60px;">
            <ul>
                <li>
                    <a href="#1"><img src="./homes/images/1.jpg" /></a>
                </li>
                <li>
                    <a href="#2"><img src="./homes/images/2.jpg" /></a>
                </li>
                <li>
                    <a href="#3"><img src="./homes/images/3.jpg" /></a>
                </li>
            </ul>
        </div>
        <h2>蜜蜂金服中心简介</h2>
        <h1>蜜蜂金服提供企业在线服务管理、培训考核、智能管理的解决方案</h1>
        <div class="wrapper con-1 clearfix">
            <div class="fl">
                <img src="./homes/images/logo.png" alt="">
                <p>&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;</p>
            </div>
            <div class="fr">
                <p>蜜蜂金服创建于2017年2月28日，历经艰苦创业，现已经跨越式发展成为中国快递行业客服团队行业领导品牌之一，是一家集专业软件科技信息：无忧挑单助手，客服助手软件，专业快递客服团队，电子商务与一体的国内大型知名服务公司。公司致力于为客户提供全方位、领先供应链解决方案，构建以快递为主，电子商务为辅，具有国内影响力的快递服务网络企业。</p>
            </div>
        </div>
        <!-- con1 end-->
        <div class="wrapper con-2">
            <h2>客服外包&nbsp;&nbsp;&nbsp;我选择蜜蜂金服</h2>
            <h3>蜜蜂金服  &bull; 服务中心</h3>
            <div class="content">
                <h4>还在为客服而烦恼吗？</h4>
                <strong>Are you still worrying about customer service?</strong>
                    <ul class="clearfix">
                        <li>
                            <img src="./homes/images/ico_1.png" alt="">
                            <p>用人成本高</p>
                        </li>
                        <li>
                            <img src="./homes/images/ico_2.png" alt="">
                            <p>服务质量差</p>
                        </li>
                        <li>
                            <img src="./homes/images/ico_3.png" alt="">
                            <p>招聘难度高</p>
                        </li>
                        <li>
                            <img src="./homes/images/ico_4.png" alt="">
                            <p>管理难度强</p>
                        </li>
                        <li>
                            <img src="./homes/images/ico_5.png" alt="">
                            <p>电话、问题件繁多</p>
                        </li>
                        <li>
                            <img src="./homes/images/ico_6.png" alt="">
                            <p>货量高峰期精疲力尽</p>
                        </li>
                    </ul>
            </div>
        </div>
        <!-- con2 end-->
        <div class="wrapper con-3">
            <h3>如果你有这些困扰，蜜蜂金服为您一站式解决所有问题</h3>
            <strong>SOLVE TROUBLES</strong>
            <ul class="clearfix">
                <li>
                    <img src="./homes/images/ico_7.png" alt="">
                    <p>管理规范 服务周到</p>
                </li>
                <li>
                    <img src="./homes/images/ico_8.png" alt="">
                    <p>简化手续 降低成本</p>
                </li>
                <li>
                    <img src="./homes/images/ico_9.png" alt="">
                    <p>解放人力 创造价值</p>
                </li>
                <li>
                    <img src="./homes/images/ico_10.png" alt="">
                    <p>提高满意度 增强归属感</p>
                </li>
                <li>
                    <img src="./homes/images/ico_11.png" alt="">
                    <p>人员稳定 收入稳定</p>
                </li>
            </ul>
        </div>
        <!-- con3 end-->
        <div class="wrapper con-4">
            <h3>你知道专职客服的成本吗？</h3>
            <strong>COST OF CUSTOMER SERVICE?</strong>
            <ul class="clearfix">
                <li class="li-1">
                    <div class="text">办公设备</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">400元/月</div>
                </li>
                <li class="li-2">
                    <div class="text">网络宽带</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">300元/月</div>
                </li>
                <li class="li-3">
                    <div class="text">主管工资</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">6000元/月/人</div>
                </li>
                <li class="li-4">
                    <div class="text">水电费用</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">500元/月</div>
                </li>
                <li class="li-5">
                    <div class="text">客服工资</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">4000元/月/人</div>
                </li>
                <li class="li-6">
                    <div class="text">社会保险</div>
                    <div class="bottom">
                        <div class="bar"></div>
                    </div>
                    <div class="btn">500元/月</div>
                </li>
            </ul>
        </div>
        <!-- con4 end-->
        <div class="wrapper con-5">
            <h3>选择蜜蜂金服的理由是</h3>
            <strong>TTHE REASON FOR CHOOSING BEE GOLDEN CLOTHES IS</strong>
            <dl>
                <dt class="clearfix">
                    <div class="fl">
                        <em>用50%成本，享受100%的服务效果</em>
                        <p>费用大大低于自己雇佣客服，<br/>节约成本，超高性价比！</p>
                    </div>
                    <div class="fr">
                        <img src="./homes/images/img_1.jpg" alt="">
                        <div class="ico"></div>
                    </div>
                </dt>
                <dd class="clearfix">
                    <div class="fl">
                        <img src="./homes/images/img_2.jpg" alt="">
                        <div class="ico"></div>
                    </div>
                    <div class="fr">
                        <em>一份工资，找到多位合适客服</em>
                        <p>5大服务基地，500余名客服，大大提升<br/>客户满意度</p>
                    </div>
                </dd>
                <dt class="clearfix">
                    <div class="fl">
                        <em>多重质检，保证服务质量</em>
                        <p>严谨的态度、高效的方法，确保服<br/>务质量，不遗漏任何服务问题</p>
                    </div>
                    <div class="fr">
                        <img src="./homes/images/img_3.jpg" alt="">
                        <div class="ico"></div>
                    </div>
                </dt>
               <dd class="clearfix">
                    <div class="fl">
                        <img src="./homes/images/img_4.jpg" alt="">
                        <div class="ico"></div>
                    </div>
                    <div class="fr">
                        <em>7*24小时客服在线</em>
                        <p>全年无休，完美解答，绝不丢失任<br/>何一笔订单，省时省力更省心！</p>
                    </div>
                </dd>
            </dl>
        </div>
        <!-- con5 end-->
        <div class="wrapper con-6">
            <h3>实地工作场景</h3>
            <strong>WORKING SCENARIO</strong>
            <ul>
                <li>
                    <img src="./homes/images/img_5.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_6.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_7.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_8.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_9.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_10.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_11.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_12.jpg" alt="">
                </li>
                <li>
                    <img src="./homes/images/img_13.jpg" alt="">
                </li>
            </ul>
        </div>
        <!-- con6 end-->
        <div class="wrapper con-7">
            <h3>员工风采</h3>
            <strong>丰富的实战经验，高品质的服务质量，效率快</strong>
            <div class="list">
                <ul class="clearfix">
                    <li>
                        <div class="box">
                            <img src="./homes/images/png_1.png" alt="">
                            <div class="title">项目管理<em>代表</em></div>
                            <p>多年项目管理经验<br/>轻松处理服务难题</p>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <img src="./homes/images/png_2.png" alt="">
                            <div class="title">质检部<em>经理</em></div>
                            <p>实时监控各项指标<br/>严格保证服务质量</p>
                        </div>
                    </li>
                    <li class="s">
                        <div class="box">
                            <img src="./homes/images/png_3.png" alt="">
                            <div class="title">技术研发<em>经理</em></div>
                            <p>平均每天工作12小时<br/>只为让客户服务更加智能</p>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <img src="./homes/images/png_4.png" alt="">
                            <div class="title">金牌<em>客服</em></div>
                            <p>从事客服工作超5年<br/>能快速解决问题件</p>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <img src="./homes/images/png_5.png" alt="">
                            <div class="title">金牌<em>客服</em></div>
                            <p>热情的服务态度<br/>对待工作认真负责</p>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <img src="./homes/images/png_6.png" alt="">
                            <div class="title">金牌<em>客服</em></div>
                            <p>专业的工作能力<br/>随时满足服务需要</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- con7 end-->
        <div class="wrapper con-8">
            <ul class="clearfix">
                <li><div class="box"><p>诚心</p></div></li>
                <li><div class="box"><p>耐心</p></div></li>
                <li><div class="box"><p>细心</p></div></li>
                <li><div class="box"><p>专心</p></div></li>
                <li><div class="box"><p>用心</p></div></li>
                <li><div class="box"><p>责任心</p></div></li>
            </ul>
        </div>
        <!-- con8 end-->
        <div class="wrapper con-9">
            <h3>服务模式</h3>
            <strong>SERVICE MODEL</strong>
            <div class="list">
                <ul class="clearfix">
                    <li class="li-1"><div class="box"><p>在线服务客服</p><div class="bg"></div></div></li>
                    <li class="li-2"><div class="box"><p>服务品质监管</p><div class="bg"></div></div></li>
                    <li class="li-3"><div class="box"><p>首创工单评价</p><div class="bg"></div></div></li>
                    <li class="li-4"><div class="box"><p>服务电话接听</p><div class="bg"></div></div></li>
                </ul>
            </div>
            <div class="bottom">快递客服外包，我选蜜蜂金服！</div>
        </div>
        <!-- con9 end-->
        <div class="wrapper con-10">
            <h3>合作流程</h3>
            <strong>COOPERATION</strong>
            <ul class="clearfix">
                <li><img src="./homes/images/ico_12.png" alt=""><p>客服咨询</p></li>
                <li><img src="./homes/images/ico_13.png" alt=""><p>为客户提供<br/>客服解决方案</p></li>
                <li><img src="./homes/images/ico_14.png" alt=""><p>协商价格 及<br/>付款问题</p></li>
                <li><img src="./homes/images/ico_15.png" alt=""><p>进行人员准备<br/>成立专项服务部门</p></li>
                <li><img src="./homes/images/ico_16.png" alt=""><p>客服A代表<br/>实地接受培训</p></li>
                <li><img src="./homes/images/ico_17.png" alt=""><p>申请快递网点<br/>客户售后专属账号</p></li>
                <li><img src="./homes/images/ico_18.png" alt=""><p>客户认可通过<br/>后正式合作</p></li>
                <li><img src="./homes/images/ico_19.png" alt=""><p>项目监控及<br/>检测服务质量</p></li>
                <li><img src="./homes/images/ico_20.png" alt=""><p>客服上网测试</p></li>
                <li><img src="./homes/images/ico_21.png" alt=""><p>公司内部<br/>客服培训</p></li>
            </ul>
        </div>
        <!-- con10 end-->
        <div class="wrapper con-11">
            <h3>合作品牌</h3>
            <strong>COOPERATION BRAND</strong>
            <div class="box">
                <ul class="clearfix ul-1">
                    <li><img src="./homes/images/img_logo_1.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_2.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_3.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_4.png" alt=""></li>
                </ul>
                <ul class="clearfix ul-2">
                    <li><img src="./homes/images/img_logo_5.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_6.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_7.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_8.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_9.png" alt=""></li>
                </ul>
                <ul class="clearfix ul-3">
                    <li><img src="./homes/images/img_logo_10.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_11.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_12.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_13.png" alt=""></li>
                </ul>
                <ul class="clearfix ul-4">
                    <li><img src="./homes/images/img_logo_14.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_15.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_16.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_17.png" alt=""></li>
                    <li><img src="./homes/images/img_logo_18.png" alt=""></li>
                </ul>
            </div>
        </div>
        <!-- con11 end-->
        <script type="text/javascript" src="{{  asset('homes/js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{  asset('homes/js/jquery.easing.1.3.js') }}"></script>
        <script type="text/javascript" src="{{  asset('homes/js/jquery.animate-colors-min.js') }}"></script>
        <script type="text/javascript" src="{{  asset('homes/js/jquery.skitter.min.js') }}"></script>
        <script type="text/javascript">	
            var box_skitter_large = null;
            $(function(){
                var _w =$(window).width();
                if(_w<1920){
                    var _boxHeight = Math.round((650/1920)*_w,10);
                    $('.box_skitter_large').css({height:_boxHeight});
                }
                $('.box_skitter_large').skitter({
                    theme: "square", 
                    numbers_align: "center", 
                    dots: true, 
                    preview: true, 
                    focus: true, 
                    focus_position: "leftTop", 
                    controls: true, 
                    controls_position: "leftTop", 
                    progressbar: true, 
                    enable_navigation_keys: true, 
                    with_animations:['cubeRandom','cube','paralell', 'glassCube','swapBars','cubeSize'],
                    onLoad: function(self) {
                        if (this.thumbs) {$('.border-skitter').height(350);}
                        box_skitter_large = self;
                    }
                });
            });
        </script>
    </body>
</html>
