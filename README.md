# 功能类
``` 
composer require qifen/webman-api-response
```
# 类
## webman Response 接口
```php
use Qifen\WebmanApiResponse\ApiResponse;
use ApiResponse;

// 如果增加其他code 可以继承 Qifen\WebmanApiResponse\Code 并修改 配置文件的类库

class CustomCode extends Code {
    const CODE_CUSTOM = 99;
    
    const EXTENDS_MAP = [
        self::CODE_CUSTOM => '自定义消息'
    ];
    
    public static function getStatusText($code, string $msg = '') {
        if (!empty($msg)) return $msg;
        $map = self::STATUS_MAP + self::EXTENDS_MAP;
        return isset($map[$code]) ? $map[$code] : '';
    }
}
