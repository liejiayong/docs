---
title: vue-router的一些要点
---

# [vue-router](https://router.vuejs.org/zh-cn/essentials/getting-started.html) 加速器
## 要点
+ 前端路由的核心:改变视图的同时不会向后端发出请求。因此未达到这目的，浏览器有两种支持：
    + hash ---> 即地址栏 URL 中的 # 符号
    + history --> 利用了 HTML5 History Interface 中的历史记录栈
        + pushState() 方法
        + replaceState() 方法
+ 在router.js配置的routes信息会映射到.vue页面的命名视图<router-view></router-view>



## 配置 router 与 .vue 的映射关系
    
    // router.js
    import Vue from 'vue'
    import VueRouter from 'vue-router'
    import Rank from 'components/Rank/Rank'
    import Toplist from 'components/Toplist/Toplist
    import Bar from 'components/Bar/Bar
    
    Vue.use(VueRouter)
    
    export default new VueRouter({
        routes: [
            {
              path: '/rank',
              component: Rank,             // `单个组件`使用 `component`
              children: [
                {
                  path: ':id',
                  components: {             // `多个组件`使用 `components`
                    default: Toplist,       // 该未命名router-view的组件默认name属性为 `default`
                    aside: Bar              // 该命名router-view的组件name属性为 `aside`
                }
            }
        ]
    })
    
    // App.vue
    <template>
      <div id="app">
        <router-view class="view-wrapper toplist"></router-view>        // 该未命名router-view的组件默认name属性为 `default`
        <router-view class="view-wrapper bar" name="aside"></router-view>       // 该命名router-view的组件name属性为 `aside`
      </div>
    </template>
    
## [动态路由匹配](https://router.vuejs.org/zh-cn/essentials/dynamic-matching.html)

## [重定向 和 别名](https://router.vuejs.org/zh-cn/essentials/redirect-and-alias.html)

## [向路由组件传递 props](https://router.vuejs.org/zh-cn/essentials/passing-props.html)
在组件中使用 $route 会使之与其对应路由形成高度耦合

+ 使用 props 将组件和路由解耦：

### 取代与 $route 的耦合
如：$route.params.id 取值耦合

    const User = {
      template: '<div>User {{ $route.params.id }}</div>'
    }
    const router = new VueRouter({
      routes: [
        { path: '/user/:id', component: User }
      ]
    })
    
### 通过 props 解耦
如：props: ['id'] 传值解耦

    const User = {
      props: ['id'],
      template: '<div>User {{ id }}</div>'
    }
    const router = new VueRouter({
      routes: [
        { path: '/user/:id', component: User, props: true },
    
        // 对于包含命名视图的路由，你必须分别为每个命名视图添加 `props` 选项：
        {
          path: '/user/:id',
          components: { default: User, sidebar: Sidebar },
          props: { default: true, sidebar: false }
        }
      ]
    })
    
## 进阶
### [导航守卫](https://router.vuejs.org/zh-cn/advanced/navigation-guards.html) 
