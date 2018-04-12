#include <phpx.h>
using namespace std;
using namespace php;

//声明函数
PHPX_FUNCTION(cpp_test);

PHPX_EXTENSION()
{
    Extension *ext = new Extension("test", "0.0.1");
    ext->registerFunction(PHPX_FN(cpp_test));
    return ext;
}

//实现函数
PHPX_FUNCTION(cpp_test)
{
    //args[1] 就是这个扩展函数的第2个参数
    long n = args[1].toInt();

    //将返回值retval初始化为数组
    Array _array(retval);

    for(int i = 0; i < n; i++) 
    {
        //args[0] 就是这个扩展函数的第一个参数
        //append方法表示向数组中追加元素
        _array.append(args[0]);
    }

}

