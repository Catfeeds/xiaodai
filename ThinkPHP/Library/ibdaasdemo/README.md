# ibdaasdemo_php
用于演示如何使用PHP，对接云蜂科技的数据接口api

项目采用原生PHP构建。

## 本项目以下三个功能：

- 提交任务
- 查询任务
- 补充任务

## 使用步骤如下：
1. 打开Config.php文件，修改三个变量“APPKEY”，“AK”和“SK”，将云蜂分配的填写进去。
2. 打开TaskSubmit.php文件，示例中是针对淘宝，客户需要根据自己的需求改动，确保该功能已经开通。填入需要提供的参数，具体可以参见api doc的各个数据页面提交任务章节。
```
php TaskSubmit.php
```
3. 任务提交成功后，会得到response，里面可以获取“taskNo”
4. 如果需要查询任务状态，打开TaskQuery.php文件，修改“taskNo”变量为第3步返回的taskNo值。运行，通过response可以获得该任务的状态信息。
```
php TaskQuery.php
```
5. 对于pending状态任务，需要补充任务信息，打开TaskPatch.php文件，修改“taskNo”字段以及调用PatchByCode相应的方法，修改PatchByCode相关的data数据，填写正确的数据，运行TaskPatch。该文件中给了各个patchCode的示例代码。
```
php TaskPatch.php
```

## 目录结构及文件说明：

- Config.php: 配置文件，包含appkey，ak，sk，以及tasktype
- Exception.php: 异常类
- PatchByCode.php: 通过patchCode补充任务信息
- TaskUtil.php: 封装了提交任务，查询任务，补充人物等方法
- TokenHelper.php: Token生成类
- Util.php: 工具类，封装了网络接口调用
- TaskSubmit.php: 提交任务
- TaskQuery.php: 查询任务
- TaskPatch.php: 补充任务，会调用PatchByCode.php


## API Doc URL:
https://doc.ibeesaas.com/daas
> 登录密码，请联系云蜂商务：bd@ibeesaas.com
