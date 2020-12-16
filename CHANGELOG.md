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