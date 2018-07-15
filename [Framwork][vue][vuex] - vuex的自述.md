---
title: vuex的自述
---

# 说在前面

最近在使用vuex做项目，所以有了总结vuex的念头。于是在本天中午到晚上9点，我一直没有停过，为了能尽快将vuex的重点写出来。虽然本vuex文档还不够完整但暂时够用，最大缺点是没有写实战，只是总结了每个知识的的要点。回想一下，时间过得很多，new Boy() 之后时间更加紧迫，有时候分心乏术。到现在，使用vue也有一段时间了。老是想总结点有关vue的却老是在写一些vue相关的demo，从而没有了总结的时间。今天总结下定决定写点vue相关的，一方面巩固基数，一方面分享总结，同时本编偏理论和一些细节，后面一部分因为官方文档也挺实用的就直接摘抄Vuex官方文档。

另外贴上一段戳心的鸡汤，请喝：
我们的敌人不是我们身外的黑暗，而是自己内心的黑暗，那就是我们的容易失望，我们的沮丧，我们的缺乏信心，耐心和细心，我们缺乏坚韧，轻言放弃，乃至自暴自弃。
	不懂的要上官网看文档，不懂的看一次两次直到弄懂为止。

# 开讲
## 1.vue
vue是一个前端javascript写的渐进式框架，在组件化模块的时候主要无非是渲染数据、增强交互效果、拓展业务逻辑组件、组件分离要高内聚低耦合、分配管理路由，在这之后就是一个挂在在浏览器端的全局js模块。当然这是我的片面之词，详情请移步vue。
	
## 2.Vuex 

可以这么通俗理解：vuex是一个挂载到vue的全局变量对象（store），而且store的 属性（state） 的 改变 只 能通过提交mutation来改变，使用Getter来映射store对象状态。另外 提交 同步事务 使用 mutation 的 commit， 分发 异步事务 使用 action 的 dispatch。同时使用 module 来方便管理 vuex模块 和 状态
	
	Vuex官方文档:https://vuex.vuejs.org/zh-cn/intro.html

>前方高能

# 是什么？
# 概念：
状态管理模式，核心是一个store（仓库），包含 共享的 单一状态（state）树

# 为什么？
## 特点：

1. 一个 全局单例 模式管理，方便集中管理所有组件状态
2. 状态管理 是 响应式 的，且高效
3. 改变状态（state）的 唯一途径 是 显示提交commit（mutation）
4. mutation->动作

# 怎么样？
## 状态相应机制：

![vuex响应机制](https://vuex.vuejs.org/zh-cn/images/vuex.png "vuex响应机制")


## 使用技巧：

1. 因为状态储存是响应式，所以 读取状态的方法 最简单的方法是使用 计算属性（computed），但建议使用辅助函数获取状态

2. Action 类似于 mutation，不同在于：
	Action 提交的是 mutation，而不是直接变更状态。
	（
		同步情况：Action -> 提交 mutation  ；  Mutation -> 提交 commit
		异步情况：Action -> 使用dispatch出发异步
	）
	Action 可以包含任意异步操作，而mutation 是同步事务。
	（Action -> 异步  ；  Mutation -> 同步）

3. 使用action分发异步事务时：
	一个 store.dispatch 在不同模块中可以触发多个 action 函数。
	在这种情况下，只有当所有触发函数完成后，返回的 Promise 才会执行。

## 核心概念
### 1、state：单一状态树，可以认为是 store 的 状态变量（data）
使用辅助函数：
获取状态：mapState
	当映射的计算属性的名称与 state 的子节点名称相同时，使用数组传入

	如：mapState（[
			'count'
		]）
	不然 传 对象
	如：mapState({
			count: state => state.count,
			countAlias: 'count'   //这里 'count' 等价于上述 state => state.count
		})
	
对象展开运算符（…）:
	使用对象展开运算符将此对象 混入 到 外部对象 中
	…mapState（{   }）
	
### 2、Getter：可以认为是 store 的计算属性（computed）

接受参数
	参数可以有state和getter本身

	如：const store = new Vuex.store({
			state: {
				todos: [
					{id: 1, text: 'id1', done: true},
					{id: 2, text: 'id2', done: false}
				]
			},
			getters: {
				doneTodos: state => {
					//这里过来state中todos的done为true的对象，并暴露为store.getters对象
					//store.getters.doneTodos // -> [{ id: 1, text: '...', done: true }]
					return state.todos.filter( todo => todo.done)
				},
				todoCount: (state, getter) => {
					return getters.doneTodos.length  // -> 1
				}
			}
		})
	
使用辅助函数：
	仅仅是将 store 中的 getter  映射 到 局部 计算属性：mapGetters
	例子：看上文
	
对象展开运算符（…）:
	使用对象展开运算符将 getter 混入 computed 对象中

	如：computed: {
		    ...mapGetters([
		      'doneTodosCount',
		      'anotherGetter',
		      // ...
		    ])
		  }
		
### 3、Mutation （ 同步 事务 ）：改变store状态的 唯一方式。
							类似于事件（Event）：每个 mutation 都有一个 字符串 的 事件类型 (type) 
							和 一个 回调函数 (handler,改变状态的地方)
接受参数
	参数可以有多个。第一参数为state，
					及其他对象或属性（支持提交负荷Payload）

	如：const store = new Vuex.Store({
		  state: {
		    count: 1
		  },
		  mutations: {
		    increment (state, n) {
		      state.count += n
		    }
		  }
		})
	触发事件：提交一次commit
	store.commit('increment', 10)
	
在组件中提交 Mutation
	两种方法：
		1.使用 this.$store.commit('xxx') （支持载荷PayLoad）
		2.使用 mapMutations 辅助函数 （支持载荷PayLoad）
			将组件中的 methods 映射为 store.commit 调用（需要在根节点注入 store）

			如：
			// xxx.vue组件
			import { mapMutations } from 'vuex'
			export default {
			  // ...
			  methods: {
			    ...mapMutations([
			      'increment', 
				// 将 `this.increment()` 映射为 `this.$store.commit('increment')`
			      'incrementBy' 
				// 将 `this.incrementBy(amount)` 映射为 `this.$store.commit('incrementBy', amount)`
			    ]),
			    ...mapMutations({
			      add: 'increment' 
				// 将 `this.add()` 映射为 `this.$store.commit('increment')`
			    })
			  }
			}
			

Mutation 需遵守 Vue 的响应规则

使用 常量 替代  Mutation 事件类型( 推荐 )
	这是一种规范，而且这样既能使用 eslint 的检测工具，也能让开发者一目了然

	如：
	//mutation-types.js
	export default {
		const SOME_MUTATION = ' SOME_MUTATION '
	}
	
	//store.js
	import Vuex from 'vuex'
	import * as types from 'mutation-types'
	
	const store = Vuex.store({
		state: { … },
		mutations: {
			[ SOME_MUTATION ]( state ) => {
				…
			}
		}
	})

Mutation 必须是同步函数（ 重点 ）
	为了实现state实时跟踪，使用同步函数，也为了调试方便

### 4、Action （ 异步 事务 ）：用法类似于mutation，不同在于可以提交 异步事务（使用dispatch 时 提交异步）， 而且 是 提交 mutation 上的事件

接受参数
	参数可以有多个。第一参数为接受一个与 store 实例具有相同方法和属性的 context 对象（因此你可以调用 context.commit 提交一个 mutation，或者通过 context.state 和 context.getters 来获取 state 和 getter），
					及其他对象或属性（支持提交负荷Payload）

	如：
	const store = new Vuex.Store({
	  state: {
	    count: 0
	  },
	  mutations: {
	    increment (state, n) {
	      state.count += n
	    }
	  },
	  actions: {
		incrementAsyn(context, n) {
			context.commit( 'increment', n )
		}
	  }
	//或者使用参数结构
	//actions: {
	//  increment( { commit } ) {
	//     commit( 'increment', n )
	//   }
      //  }
	
	})

分发Action
	因为Action提交的commit实际是提交mutation，而mutation的提交必须是同步的，
	要向提交异步的action必须使用dispatch

	如：
	const store = new Vuex.Store({
	  state: {
	    count: 0
	  },
	  mutations: {
	    increment (state, amount) {
	      state.count += amount
	    }
	  },
	  actions: {
		incrementAsyn( { commit } ) {
			setTimeout( () => {
				commit( 'increment' )
			}, 1000)
		}
	   }
	});
	
	// 以载荷形式分发
	store.dispatch( 'incrementAsyn', {
		amount: 10
	} )
	
	// 以对象形式分发
	store.dispatch( {
		type: 'incrementAsyn', 
		amount: 10
	} )
	
	例子2：
	来看一个更加实际的购物车示例，涉及到调用异步 API 和分发多重 mutation：
	
	actions: {
	  checkout ({ commit, state }, products) {
	    // 把当前购物车的物品备份起来
	    const savedCartItems = [...state.cart.added]
	    // 发出结账请求，然后乐观地清空购物车
	    commit(types.CHECKOUT_REQUEST)
	    // 购物 API 接受一个成功回调和一个失败回调
	    shop.buyProducts(
	      products,
	      // 成功操作
	      () => commit(types.CHECKOUT_SUCCESS),
	      // 失败操作
	      () => commit(types.CHECKOUT_FAILURE, savedCartItems)
	    )
	  }
	}
	

在组件中 分发 Action
两种方法：
1. 使用 this.$store.dispatch('xxx') （支持载荷PayLoad）
2. 使用 mapActions 辅助函数 （支持载荷PayLoad）
	将组件中的 methods 映射为 store.commit 调用（需要在根节点注入 store）

	如：
	// xxx.vue组件
	import { mapActions } from 'vuex'
	export default {
	  // ...
	  methods: {
	   ...mapActions([
	      'increment', 
		// 将 `this.increment()` 映射为 `this.$store.dispatch('increment')`
	      'incrementBy' 
		// 将 `this.incrementBy(amount)` 映射为 `this.$store.dispatch('incrementBy', amount)`
	    ]),
	    ...mapActions({
	      add: 'increment' 
		// 将 `this.add()` 映射为 `this.$store.dispatch('increment')`
	    })
	  }
	}

组合Action
	那么既然使用actions来 异步 分发改变状态，
	因此也要使用到 Promise ，
	和 asyn/await 的新知识的

	使用 Promise
	actions: {
		actionA( {commit} ){
			return new Promise( (resolve, reject) => {
				setTimeout( () => {
					commit('someMutation')
					resolve()
				}, 1000)
			})
		},
		actionB( {dispatch, commit} ){
			return dispatch('actionA).then( () => {
				commit('someOtherMutation')
			})
		}
	},
	// xxx.vue组件
	methods： {
	this.$store.dispatch('actionA').then(() => {
		…
	})
	}
	
	
	使用  asyn/await 
	//假设 getData() 和 getOtherData() 返回的是 Promise

	actions: {
		async actionA ( {commit} ) {
			commit('gotData', await getData())
		},
		async actionB ( {commit} ) {
			await dispatch('actionA') 
			//等待actionA完成
			commit('gotOtherData', await getOtherData())
		}
	}

注意：
一个 store.dispatch 在不同模块中可以触发多个 action 函数。
在这种情况下，只有当所有触发函数完成后，返回的 Promise 才会执行。

### 5.Module
	由于使用单一状态树，应用的所有状态会集中到一个比较大的对象。当应用变得非常复杂时，store 对象就有可能变得相当臃肿。
	
	为了解决以上问题，Vuex 允许我们将 store 分割成模块（module）。每个模块拥有自己的 state、mutation、action、getter、甚至是嵌套子模块——从上至下进行同样方式的分割：
	
	const moduleA = {
	  state: { ... },
	  mutations: { ... },
	  actions: { ... },
	  getters: { ... }
	}
	
	const moduleB = {
	  state: { ... },
	  mutations: { ... },
	  actions: { ... }
	}
	
	const store = new Vuex.Store({
	  modules: {
	    a: moduleA,
	    b: moduleB
	  }
	})
	
	store.state.a // -> moduleA 的状态
	store.state.b // -> moduleB 的状态
	
	模块的局部状态
		对于模块内部的 mutation 和 getter，接收的第一个参数是模块的局部状态对象。

		如：
		const moduleA = {
		  state: { count: 0 },
		  mutations: {
		    increment (state) {
		      // 这里的 `state` 对象是模块的局部状态
		      state.count++
		    }
		  },
		  getters: {
		    doubleCount (state) {
		      return state.count * 2
		    }
		  }
		}
		
		同样，对于模块内部的 action，局部状态通过 context.state 暴露出来，
		根节点状态则为 context.rootState：
		如：
		const moduleA = {
		  // ...
		  actions: {
		    incrementIfOddOnRootSum ({ state, commit, rootState }) {
		      if ((state.count + rootState.count) % 2 === 1) {
		        commit('increment')
		      }
		    }
		  }
		}
		对于模块内部的 getter，根节点状态会作为第三个参数暴露出来：
		如:
		const moduleA = {
		  // ...
		  getters: {
		    sumWithRootCount (state, getters, rootState) {
		      return state.count + rootState.count
		    }
		  }
		}
		
	命名空间
		默认情况下，module中的{ state， actions, getters } 注册 在 全局变量上， 
		使得多个模块能够对同一 mutation 或 action 作出响应。
		
		如果希望 模块具有更高的封装度和复用性，可以通过 添加  namespaced: true  的方式使其成为命名空间模块。
		当模块被注册后，它的所有 getter、action 及 mutation 都会自动根据模块注册的路径调整命名。例如：

		如：
		const store = new Vuex.Store({
		  modules: {
		    account: {
		      namespaced: true,
		
		      // 模块内容（module assets）
		      state: { ... }, // 模块内的状态已经是嵌套的了，使用 `namespaced` 属性不会对其产生影响
		      getters: {
		        isAdmin () { ... } // -> getters['account/isAdmin']
		      },
		      actions: {
		        login () { ... } // -> dispatch('account/login')
		      },
		      mutations: {
		        login () { ... } // -> commit('account/login')
		      },
		      // 嵌套模块
		      modules: {
		        // 继承父模块的命名空间
		        myPage: {
		          state: { ... },
		          getters: {
		            profile () { ... } // -> getters['account/profile']
		          }
		        },
		        // 进一步嵌套命名空间
		        posts: {
		          namespaced: true,
		          state: { ... },
		          getters: {
		            popular () { ... } // -> getters['account/posts/popular']
		          }
		        }
		      }
		    }
		  }
		})
		启用了命名空间的 getter 和 action 会收到局部化的 getter，dispatch 和 commit。换言之，你在使用模块内容（module assets）时不需要在同一模块内额外添加空间名前缀。更改 namespaced 属性后不需要修改模块内的代码。
		
	在命名空间模块内访问全局内容（Global Assets）
		如果希望 使用全局  state  和 getter，rootState 和 rootGetter 会作为第三和第四参数传入 getter，也会通过 context 对象的属性传入 action。

		{
			命名模块（module）内 使用 全局 state 和 getter
			在命名模块（module）的getterr 传入 rootState 和 rootGetter
		}
		若需要在全局命名空间内分发 action 或提交 mutation，将 { root: true } 作为第三参数传给 dispatch 或 commit 即可。
		{
			全局命名空间 内 分发 action 或 提交 mutation
			则在 action 或 mutation 内
			  dispatch('someOtherAction', null, { root: true }) // -> 'someOtherAction'
			  dispatch('someOtherAction', null, { root: true }) // -> 'someOtherAction'
		}

		如：
		modules: {
		  foo: {
		    namespaced: true,
		
		    getters: {
		      // 在这个模块的 getter 中，`getters` 被局部化了
		      // 你可以使用 getter 的第四个参数来调用 `rootGetters`
		      someGetter (state, getters, rootState, rootGetters) {
		        getters.someOtherGetter // -> 'foo/someOtherGetter'
		        rootGetters.someOtherGetter // -> 'someOtherGetter'
		      },
		      someOtherGetter: state => { ... }
		    },
		
		    actions: {
		      // 在这个模块中， dispatch 和 commit 也被局部化了
		      // 他们可以接受 `root` 属性以访问根 dispatch 或 commit
		      someAction ({ dispatch, commit, getters, rootGetters }) {
		        getters.someGetter // -> 'foo/someGetter'
		        rootGetters.someGetter // -> 'someGetter'
		
		        dispatch('someOtherAction') // -> 'foo/someOtherAction'
		        dispatch('someOtherAction', null, { root: true }) // -> 'someOtherAction'
		
		        commit('someMutation') // -> 'foo/someMutation'
		        commit('someMutation', null, { root: true }) // -> 'someMutation'
		      },
		      someOtherAction (ctx, payload) { ... }
		    }
		  }
		}

带命名空间的绑定函数
	当使用 mapState, mapGetters, mapActions 和 mapMutations 这些函数来绑定命名空间模块时，写起来可能比较繁琐：
	如：
	computed: {
	  ...mapState({
	    a: state => state.some.nested.module.a,
	    b: state => state.some.nested.module.b
	  })
	},
	methods: {
	  ...mapActions([
	    'some/nested/module/foo',
	    'some/nested/module/bar'
	  ])
	}
	解决方法：
	1、对于这种情况，可以将模块的空间名称字符串作为第一个参数传递给上述函数，这样所有绑定都会自动将该模块作为上下文。于是上面的例子可以简化为：
	
	computed: {
	  ...mapState('some/nested/module', {
	    a: state => state.a,
	    b: state => state.b
	  })
	},
	methods: {
	  ...mapActions('some/nested/module', [
	    'foo',
	    'bar'
	  ])
	}
	2、通过使用 createNamespacedHelpers 创建基于某个命名空间辅助函数。它返回一个对象，对象里有新的绑定在给定命名空间值上的组件绑定辅助函数：
	
	import { createNamespacedHelpers } from 'vuex'
	
	const { mapState, mapActions } = createNamespacedHelpers('some/nested/module')
	
	export default {
	  computed: {
	    // 在 `some/nested/module` 中查找
	    ...mapState({
	      a: state => state.a,
	      b: state => state.b
	    })
	  },
	  methods: {
	    // 在 `some/nested/module` 中查找
	    ...mapActions([
	      'foo',
	      'bar'
	    ])
	  }
	}
	
给插件开发者的注意事项
	如果开发的插件（Plugin）提供了模块并允许用户将其添加到 Vuex store，可能需要考虑模块的空间名称问题。对于这种情况，你可以通过插件的参数对象来允许用户指定空间名称：

	如：
	// 通过插件的参数对象得到空间名称
	// 然后返回 Vuex 插件函数
	export function createPlugin (options = {}) {
	  return function (store) {
	    // 把空间名字添加到插件模块的类型（type）中去
	    const namespace = options.namespace || ''
	    store.dispatch(namespace + 'pluginAction')
	  }
	}
	
模块动态注册
	在 store 创建之后，你可以使用 store.registerModule 方法注册模块：

	如：
	// 注册模块 `myModule`
	store.registerModule('myModule', {
	  // ...
	})
	// 注册嵌套模块 `nested/myModule`
	store.registerModule(['nested', 'myModule'], {
	  // ...
	})
	之后就可以通过 store.state.myModule 和 store.state.nested.myModule 访问模块的状态。
	
	模块动态注册功能使得其他 Vue 插件可以通过在 store 中附加新模块的方式来使用 Vuex 管理状态。例如，vuex-router-sync 插件就是通过动态注册模块将 vue-router 和 vuex 结合在一起，实现应用的路由状态管理。
	
	你也可以使用 store.unregisterModule(moduleName) 来动态卸载模块。注意，你不能使用此方法卸载静态模块（即创建 store 时声明的模块）。
	
	在注册一个新 module 时，你很有可能想保留过去的 state，例如从一个服务端渲染的应用保留 state。你可以通过 preserveState 选项将其归档：store.registerModule('a', module, { preserveState: true })。
	
	模块重用
	有时我们可能需要创建一个模块的多个实例，例如：
	
	创建多个 store，他们公用同一个模块 (例如当 runInNewContext 选项是 false 或 'once' 时，为了在服务端渲染中避免有状态的单例)
	在一个 store 中多次注册同一个模块
	如果我们使用一个纯对象来声明模块的状态，那么这个状态对象会通过引用被共享，导致状态对象被修改时 store 或模块间数据互相污染的问题。
	
	实际上这和 Vue 组件内的 data 是同样的问题。因此解决办法也是相同的——使用一个函数来声明模块状态（仅 2.3.0+ 支持）：

	如：
	const MyReusableModule = {
	  state () {
	    return {
	      foo: 'bar'
	    }
	  },
	  // mutation, action 和 getter 等等...
	}
	
	
## 项目结构
Vuex 并不限制你的代码结构。但是，它规定了一些需要遵守的规则：
1. 应用层级的状态应该集中到单个 store 对象中。
2. 提交 mutation 是更改状态的唯一方法，并且这个过程是同步的。
3. 异步逻辑都应该封装到 action 里面。

只要你遵守以上规则，如何组织代码随你便。如果你的 store 文件太大，只需将 action、mutation 和 getter 分割到单独的文件。
对于大型应用，我们会希望把 Vuex 相关代码分割到模块中。下面是项目结构示例：

		├── index.html
		├── main.js
		├── api
		│   └── ... # 抽取出API请求
		├── components
		│   ├── App.vue
		│   └── ...
		└── store
				├── index.js          # 我们组装模块并导出 store 的地方
				├── actions.js        # 根级别的 action
				├── mutations.js      # 根级别的 mutation
				└── modules
						├── cart.js       # 购物车模块
						└── products.js   # 产品模块



原文出自本人博客：
[vuex的详细总结](http://www.twicetech.top/twicetech-top-vuex-detail-summary/ )
[博主博客--兼乎](http://upload-images.jianshu.io/upload_images/2767489-8bc580db406e6728.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240 "兼乎")