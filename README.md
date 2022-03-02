# 功能类
``` 
composer require qifen/webman-api-response
```
# 类
## webman Response 接口
```php
use Qifen\WebmanApiResponse\ApiResponse;
use ApiResponse;

//如果增加其他code 可以继承 Qifen\WebmanApiResponse\Code 并修改 配置文件的类库

class QifenCode extends Code {
    const XX = 1;
    public $_EXTENDS_MAP = [
        self::XX = 'xxxx'
    ];
}
