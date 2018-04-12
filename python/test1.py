# -*- coding:UTF-8 -*-

from package_runoob.runoob1 import runoob1
from package_runoob.runoob2 import runoob2

runoob1()
runoob2()


#定义函数
def printme( str ):
    print str;
    return;

printme("我要调用创建的函数")

def ChangeInt( a ):
    a = 10

b = 2
ChangeInt(b)
print b

def changeme( mylist ):
    mylist.append([1,2,3,4])
    print mylist
    return

mylist = [10,20,30]
changeme( mylist )
print mylist

