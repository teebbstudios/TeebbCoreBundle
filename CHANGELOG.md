#CHANGELOG

0.1.1:  
1. fosckeditorbundle 添加文件图片拖拽上传和图片上传功能
2. 用户组权限调整，不同用户组可设置不同的ckeditor工具栏配置
3. 文本、长文本字段默认过滤所有HTML标签

0.1.2: 
1. 邮件发送fromAddress去除硬编码，改为在teebb_core.yaml文件中手动配置且必须配置

0.1.3
1. Kernel版本号修改
2. block ContentsBlockService 添加类型参数用于获取指定类型内容列表
3. 添加"用户字段"菜单，对用户字段进行管理，用户默认增加头像字段。用户表单调整。
4. 添加Twig函数： show_content_all_fields 获取并显示当前内容所有字段；get_content_field 获取当前内容指定字段数据
5. 添加Twig macro方法：showContentAllFieldsData 用此macro可方便的显示当前内容所有字段

0.1.4
1. Admin头像bug修改

0.1.5
1. teebb.core.block.contents Block调整过滤条件，自由度更高

0.1.6
1. 添加 teebb.core.block.contents_in_taxonomy 获取某分类下所有内容列表
2. 评论列表及表单显示调整
3. RouteParameterUnderLineFixSubscriber bug修复

0.1.7
1. 文件管理添加 用户仅允许删除自己上传的文件 权限
2. 内容评论列表代码优化
3. 字段数据添加缓存

0.1.8
1. 可在twig中手动添加页面DOM块级元素缓存，增加菜单、Option缓存消息，对应表单修改后删除缓存
2. 允许用户更新、删除自己的内容的权限调整
3. FileVoter 权限Bug修复

0.1.9
1. 字段显示macro调整
2. 内容列表页面调整
3. 可以使用entity_type获取某内容指定字段类型的数据
4. 内容置顶

0.1.10
1. 添加twig方法get_specify_type_field获取内容指定类型的所有字段的数据

0.1.11
1. 修改动态Mapping字段功能，改为Doctrine订阅器

0.1.12
1. 添加缓存过期配置，统一缓存失效时间
2. bug修复，评论表单按钮样式调整

0.1.13
1. 评论列表样式修改

0.1.14  
1. 修复字段摘要信息的显示问题
2. 字段设置表单bug修复

0.1.15
1. teebb.core.block.contents 添加排除条件设置，可以排除不需要的结果

0.1.16
1. 内容管理批量管理BUG修复
2. 添加前台页面内容搜索Block

0.1.17
1. ckeditor添加codesnippet插件

0.1.18
1. 引字分类字段，添加新词汇时自动添加到对应的分类类型
2. 引用用户字段完成
2. 当不限制字段数量时，添加删除字段按钮可动态删字段行

0.1.19
1. 添加ApiProblemException用于提示Ajax错误信息
2. 翻译修复

0.1.20
1. ckeditor添加markdown插件

0.1.21
1. 控制台内容列表标题链接到teebb_content_show路由
2. 更新到symfony5.3.6版本支持
3. 使用新的用户登录认证器类AppLoginFormAuthenticator
4. 字段数据保存后添加事件
5. 用户登录错误次数限制