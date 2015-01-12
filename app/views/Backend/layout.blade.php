<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="O2OMobile管理中心" />
  <meta name="author" content="Joy" />
  <title>O2OMobile管理中心</title>
  <link rel="stylesheet" href="{{ asset('/backend/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/backend/css/font-icons/entypo/css/entypo.css') }}" >
  <link rel="stylesheet" href="{{ asset('/backend/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/backend/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('/backend/css/forms.css') }}">
  @yield('page_css')
  <link rel="stylesheet" href="{{ asset('/backend/css/custom.css') }}">
  <script src="{{ asset('/backend/js/jquery-1.11.0.min.js') }}"></script>
  <!--[if lt IE 9]>
  <script src="{{ asset('/backend/js/ie8-responsive-file-warning.js') }}"></script>
  <![endif]-->
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="page-body">
  <div class="page-container @if(isset($_COOKIE['sidebar_status']) && $_COOKIE['sidebar_status'] == 'hide') sidebar-collapsed @endif">
    <div class="sidebar-menu">
      <header class="logo-env">
        <!-- logo -->
        <div class="logo">
          <a href="{{url('admin')}}">
            <h1>O2OMobile</h1>
          </a>
        </div>
        <!-- logo collapse icon -->
        <div class="sidebar-collapse">
          <a href="#" class="sidebar-collapse-icon with-animation">
            <!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition --> <i class="entypo-menu"></i>
          </a>
        </div>
        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
          <a href="#" class="with-animation">
            <!-- add class "with-animation" to support animation --> <i class="entypo-menu"></i>
          </a>
        </div>
      </header>
      <ul id="main-menu" class="">
      @if (isset($menus))
      @foreach ($menus as $menu)
        <?php $isActive = is_current_model($menu['pattern']) ?>
        <li @if($isActive) class="opened" @endif>
          <a href="{{ !empty($menu['url']) ? url($menu['url']) : '#'}}">
            <i class="{{$menu['icon']}}"></i>
            <span>{{$menu['name']}}</span>
          </a>
          @if(!empty($menu['submenu']))
          <ul @if($isActive) class="visible" @endif>
            @foreach($menu['submenu'] as $submenu)
            <li>
              <a href="{{ url($submenu['url']) }}">
                <span>{{$submenu['name']}}</span>
              </a>
            </li>
            @endforeach
          </ul>
          @endif
        </li>
        @endforeach
        @endif
      </ul>
    </div>
    <div class="main-content container">
      <div class="row">
        <!-- Profile Info and Notifications -->
        <div class="col-md-6 col-sm-8 clearfix">
          <ul class="user-info pull-left pull-none-xsm">
            <!-- Profile Info -->
            <li class="profile-info dropdown">
              <!-- add class "pull-right" if you want to place this from right -->
              @yield('page_title')
            </li>
          </ul>
        </div>
        <!-- Raw Links -->
        <div class="col-md-6 col-sm-4 clearfix hidden-xs">
          <ul class="list-inline links-list pull-right">
            <li>
                {{Auth::user()->username}}
                <span class="badge badge-success chat-notifications-badge is-hidden">0</span>
            </li>
            <li class="sep"></li>
            <li>
              <a href="{{url('/admin/auth/logout')}}">
                注销登录
                <i class="entypo-logout right"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <hr />
      <div class="message-container">
        @if(Session::has('message'))
        <div class="form-group">
          <div class="tips {{ 'text-'. Session::get('color', 'info') }}">
          <i class="pull-right">✕</i>
          {{ Session::get('message') }}
          </div>
        </div>
        @endif
        @if(count($errors->all()))
        <div class="tips text-danger">
            <i class="pull-right">✕</i>
            <ul>
            @foreach($errors->all('<li class="pad-y-5">:message</li>') as $error)
                {{$error}}
            @endforeach
            </ul>
        </div>
        @endif
      </div>
      <div class="inner-page-container">
        @yield('content')
      </div>
      <!-- Footer -->
      <footer class="main">
        &copy; {{date('Y')}} <strong>O2OMobile管理中心</strong>
        Powered by
        <a href="http://writor.me" target="_blank">Writor Blog Framework</a>
      </footer>
    </div>
  </div>
  <div class="modal fade" id="modal-alert" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">提示</h4>
              </div>
              <div class="modal-body" id="_alert_message">

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">取消</button>
              </div>
          </div>
      </div>
  </div>
  <script src="{{ asset('/backend/js/gsap/main-gsap.js') }}" id="script-resource-1"></script>
  <script src="{{ asset('/backend/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}" id="script-resource-2"></script>
  <script src="{{ asset('/backend/js/bootstrap.js') }}" id="script-resource-3"></script>
  <script src="{{ asset('/backend/js/joinable.js') }}" id="script-resource-4"></script>
  <script src="{{ asset('/backend/js/resizeable.js') }}" id="script-resource-5"></script>
  <script src="{{ asset('/backend/js/api.js') }}" id="script-resource-6"></script>
  <script src="{{ asset('/backend/js/cookies.min.js') }}" id="script-resource-7"></script>
  @yield('page_js')
  <script src="{{ asset('/backend/js/admin-common.js') }}" id="script-resource-23"></script>
</body>
</html>