<html>
<head>
    <title>Laravel gii</title>
    <link rel="stylesheet" href="{{URL::asset('gii_assets/styles/iview.css')}}">
</head>
<body>
<style scoped>
    .layout {
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }

    .layout-logo {
        width: 200px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        font-weight: bold;
        color: white;
        font-size: 32px;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
    }

    .layout-nav {
        width: 420px;
        margin: 0 auto;
        margin-right: 20px;
    }
</style>
<div id="app">
    <div class="layout">
        <i-layout>
            <i-header>
                <i-menu mode="horizontal" theme="dark" active-name="1">
                    <div class="layout-logo">Laravel gii</div>
                </i-menu>
            </i-header>
            <i-layout>
                <i-sider hide-trigger :style="{background: '#fff'}">
                    <i-menu active-name="1-2" theme="light" width="auto" :open-names="['1']">
                        <i-menu-item name="1" to="/gii/model">Create Model</i-menu-item>
                        <i-menu-item name="2" to="/gii/crud">Create CURD</i-menu-item>
                    </i-menu>
                </i-sider>
                <i-layout :style="{padding: '0 24px 24px'}">
                    <i-content :style="{padding: '24px', minHeight: '280px', background: '#fff'}">
                        @yield('content')
                    </i-content>
                </i-layout>
            </i-layout>
        </i-layout>
    </div>
</div>
<script src="{{URL::asset('gii_assets/vue.min.js')}}"></script>
<script src="{{URL::asset('gii_assets/iview.min.js')}}"></script>
@yield('assets')
@section('new_vue')
<script>
    var vm = new Vue({
    }).$mount('#app')
</script>
@show
</body>
</html>