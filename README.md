# 链路日志

规范链路日志格式，方便 Kibana 收集分析。

链路日志记录的对象是函数调用，所以暴露出的接口也只是针对函数调用的，详见使用说明。

# 安装

1. composer 引入

    ```php
    composer require nucarf/trace-log:dev-master
    ```

2. 启动配置

  在程序启动时进行配置（例如在 Laravel 的 Service Provider 中配置）

  ```php
  use \Nucarf\TraceLog\TraceLogConfigurator;

  // 设置日志路径
  TraceLogConfigurator::setPath('/var/log/php/laravel.log')

  // 设置程序名称和日志方式
  // - 程序名：lumen
  // - 日志方式：single 即打印到单个文件，文件路径为上面 setPath
  TraceLogConfigurator::config('lumen', 'single');

  // 设置日志上下文信息的获取方式
  // 例如：日志中的当前用户信息
  TraceLogConfigurator::setContextReader(function () {
        return [
            'userId' => 1,
            'userType' => 'staff',
        ];
  })
  ```


# 使用说明

### 参数说明

```php
\Nucarf\TraceLog\TraceLog::method(
    $method,
    $args,
    $return,
    $cost,
    Throwable $exception = null
);

// or

nucarf_trace_log(...)
```

- method, 函数名称
  - 类型：string
  - 说明：如果是成员函数或静态函数，推荐加上类名，eg：`\HttpClient::get`
- args, 函数参数
  - 类型：mixed，如果是非字符串类型，打印时会进行 json_encode
- return, 函数返回值
  - 类型：同 args
- cost, 调用此函数耗费的时间
  - 类型：int、float、string，如果是字符串必须是 numberic
  - 说明：推荐精确到毫秒，打印时会精确到小数点后 3 位
- exception，函数抛出的异常
  - 类型：Throwable

#### 示例

```php
class HttpClient
{

    public function get($url)
    {
        $start = microtime(true);
        try {

            $result = $this->request('GET', $url);
            return $result;

        } catch (\Throwable $exception) {

            throw $exception;

        } finally {

            nucarf_trace_log(
                __METHOD__,                 // 函数名
                func_get_args(),            // 参数列表
                $result ?? null,            // try 中创建 $result 变量
                microtime(true) - $start,   // 执行时间
                $exception ?? null          // 抛出的异常
            );

        }
    }
}
```
