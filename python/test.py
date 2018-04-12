# -*- coding: UTF-8 -*-

import sys
import time
import calendar

print time.time()
print time.localtime(time.time())

print sys.argv




counter = 100
mailes =  1000.0
name = "john"

print counter
print mailes
print name

str = 'Hello World!'
print str
print str[0]
print str[2:5]
print str[2:]
print str * 2
print str + "TEST"

list = ['runoob',786,2.23,'john',70.2]
tinylist = [123,'john']

print list
print list[0]
print list[1:3]
print list[2:]
print tinylist * 2
print list + tinylist

dict = {}
dict['one'] = "this is one"
dict[2] = "this is two"

tinydict = {'name':'john','code':6734,'dept':'sales'}

print dict['one']
print dict[2]
print tinydict
print tinydict.keys()
print tinydict.values()


x="a"
y="b"

print x
print y

print x,
print y,

print x,y

a = 20
b = 10
c = 15
d = 5
e = 0
e = (a+b) * c/d
print e
e = ((a+b) *c) /d
print e
e = (a+b) * (c/d)
print e
e = a + (b*c)/d
print e




flag = False
name = 'luren'
if name == 'python':
     flag = True
     print 'welcome boss'
else:
     print name



num = 5
if num == 3:
    print 'boss'
elif num == 2:
    print 'user'
elif num == 1:
    print 'worker'
elif num < 0:
    print 'error'
else:
    print 'roadman'


num = 9
if num >= 0 and num <= 10:
    print 'hello'

num = 10
if num < 0 and num > 10:
    print 'test'


numbers = [12,37,5,42,8,3]
even = []
odd = []
while len(numbers) > 0 :
    number = numbers.pop()
    if (number % 2 == 0) :
        even.append(number)
    else:
        odd.append(number)

print even
print odd


count = 0
while (count < 9):
    print 'the count is:',count
    count = count + 1

print 'good bye'

for letter in 'python':
    print '当前字母:', letter

fruits = ['banana', 'apple', 'mango']
for fruit in fruits:
    print '当前水果:', fruit

print 'good bye'

fruits = ['banana','apple','mango']
for index in range(len(fruits)):
    print '当前水果：',fruits[index]


i = 2
while(i < 100):
    j = 2
    while(j <= (i/j)):
      if not(i%j):break
      j = j+1
    if (j> i/j):print i,'是素数'
    i = i+1


print time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
print calendar.month(2016, 1)

